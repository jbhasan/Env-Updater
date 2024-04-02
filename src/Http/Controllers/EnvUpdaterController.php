<?php
namespace Sayeed\EnvUpdater\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class EnvUpdaterController extends Controller
{
	public $permissionFile = 'framework/env-permission';
	public $permissionHistoryFile = 'framework/env-permission-history';
	public $logged_user = [];
	public $version;
	public function __construct()
	{
		$this->version = $this->getPackageVersion('sayeed/env-updater');
		$this->middleware(function($request, $next){
			$envPermission = storage_path($this->permissionFile);
			if(file_exists($envPermission)) {
				$envPermissionData = file_get_contents($envPermission);
				$envPermissionData = json_decode($envPermissionData, true);
				if(isset($envPermissionData['user-agent']) && $envPermissionData['user-agent'] == base64_encode($request->header('User-Agent')) && isset($envPermissionData['expired']) && $envPermissionData['expired'] > date('Y-m-d H:i:s')) {
					$this->logged_user = $envPermissionData;
					return $next($request);
				} else {
					return redirect('/env-updater');
				}
			} else {
				return redirect('/env-updater');
			}
		}, ['except' => ['envPermission', 'envPermissionUpdate']]);
	}
	protected function getPackageVersion($packageName)
    {
        $file = base_path().'/composer.lock';
        $packages = json_decode(file_get_contents($file), true)['packages'];
        foreach ($packages as $package) {
            if ($package['name'] == $packageName) {
                return $package['version'];
            }
        }
        
        return null;
    }

	public function envPermission(Request $request) {
		$envPermission = storage_path($this->permissionFile);
		if(file_exists($envPermission)) {
			$envPermissionData = file_get_contents($envPermission);
			$envPermissionData = json_decode($envPermissionData, true);
			if(isset($envPermissionData['user-agent']) && $envPermissionData['user-agent'] == base64_encode($request->header('User-Agent')) && isset($envPermissionData['expired']) && $envPermissionData['expired'] > date('Y-m-d H:i:s')) {
				return redirect('/env-updater/edit')->with('success', 'Already logged in');
			}
		}
		$version = $this->version;
		return view('env_updater::permission-env', compact('version'));
	}
	public function envPermissionUpdate(Request $request) {
		try {
			$envPermission = storage_path($this->permissionFile);
			$updated_data = [
				'email' => $request->email,
				'mobile' => $request->mobile,
				'time' => date('Y-m-d H:i:s'),
				'ip' => $request->ip(),
				'expired' => date('Y-m-d H:i:s', strtotime('+1hours')),
				'user-agent' => base64_encode($request->header('User-Agent'))
			];
			file_put_contents($envPermission, json_encode($updated_data));
		} catch(\Exception $e) {
			return redirect()->back()->with('error', 'Problem in changing... '. $e->getMessage());
		}
		return redirect('/env-updater/edit')->with('success', 'Successfully added');
	}
	public function envPermissionLogout() {
		$envPermission = storage_path($this->permissionFile);
		file_put_contents($envPermission, json_encode([]));
		return redirect('/env-updater/edit')->with('success', 'Successfully logged out');
	}
	public function showEnv() {
		$envPermission = storage_path($this->permissionFile);
		if (!file_exists($envPermission)) {
			return redirect('/env-updater');
		}
		$envPermissionData = file_get_contents($envPermission);
		$envPermissionData = json_decode($envPermissionData, true);
		if(!isset($envPermissionData['expired']) || $envPermissionData['expired'] < date('Y-m-d H:i:s')) {
			return redirect('/env-updater');
		}

		$env_file_path = base_path('.env');
		if(file_exists($env_file_path)) {
			$file_content = file_get_contents($env_file_path);
			$file_name = '.env';

			$permissionHistoryFile = storage_path($this->permissionHistoryFile);
			$permissionHistoryFileData = [];
			if(file_exists($permissionHistoryFile)) {
				$permissionHistoryFileData = file_get_contents($permissionHistoryFile);
				$permissionHistoryFileData = json_decode($permissionHistoryFileData, true);
			}

			$version = $this->version;
			return view('env_updater::update-env', compact('file_content', 'file_name', 'permissionHistoryFileData', 'version'));
		}
	}
	public function updateEnv(Request $request) {
		$env_file_path = base_path('.env');
		if(file_exists($env_file_path)) {
			$file_content = file_get_contents($env_file_path);
			$edited_data = str_replace("\r\n", "\n", $request->edited_data);
			if(trim($edited_data) == trim($file_content)) {
				return redirect()->back()->with('error', 'No changes were made');
			}

			try {
				file_put_contents($env_file_path, $edited_data);

				$permissionHistoryFile = storage_path($this->permissionHistoryFile);
				if(file_exists($permissionHistoryFile)) {
					$permissionHistoryFileData = file_get_contents($permissionHistoryFile);
					$permissionHistoryFileData = json_decode($permissionHistoryFileData, true);
				}
				$permissionHistoryFileData[] = [
					'email' => $this->logged_user['email'],
					'mobile' => $this->logged_user['mobile'],
					'prev_data' => base64_encode($file_content),
					'new_data' => base64_encode($edited_data),
					'changed_at' => date('Y-m-d H:i:s')
				];
				file_put_contents($permissionHistoryFile, json_encode($permissionHistoryFileData));

				Artisan::call('config:clear');
			} catch(\Exception $e) {
				return redirect()->back()->with('error', 'Problem in changing... '. $e->getMessage());
			}
			return redirect()->back()->with('success', 'Successfully changed');
		}
	}
}
