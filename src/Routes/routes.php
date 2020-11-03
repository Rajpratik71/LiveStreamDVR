<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

use App\TwitchHelper;
use App\TwitchAutomator;
use App\Controller\AboutController;
use App\Controller\DashboardController;
use App\Controller\HookController;
use App\Controller\VodController;
use App\Controller\PlayerController;
use App\Controller\SettingsController;
use App\Controller\SubController;
use App\Controller\ApiController;
use App\Controller\DebugController;
use App\Controller\CronController;
use App\Controller\ToolsController;
use App\TwitchConfig;

// Define named route
$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
    $response->getBody()->write("Hello, World! Visit <a href=\"./dashboard\">/dashboard</a> to continue.<br>Slim 4 doesn't seem to support relative redirects.");
    return $response;
})->setName('index');

$app->get('/dashboard', DashboardController::class . ':dashboard')->setName('dashboard');
$app->get('/about', AboutController::class . ':about')->setName('about');

$app->get('/settings', SettingsController::class . ':settings')->setName('settings');
$app->post('/settings/save', SettingsController::class . ':settings_save')->setName('settings_save');
$app->post('/settings/streamer/add', SettingsController::class . ':streamer_add')->setName('streamer_add');
$app->post('/settings/streamer/delete', SettingsController::class . ':streamer_delete')->setName('streamer_delete');
$app->post('/settings/streamer/update', SettingsController::class . ':streamer_update')->setName('streamer_update');
$app->post('/settings/favourites/save', SettingsController::class . ':favourites_save')->setName('favourites_save');

$app->get('/player/{vod}', PlayerController::class . ':player')->setName('player');
$app->post('/cut', VodController::class . ':cut')->setName('cut');
$app->get('/chat/{vod}', VodController::class . ':chat')->setName('chat');
$app->get('/save/{vod}', VodController::class . ':save')->setName('save');
$app->get('/delete/{vod}', VodController::class . ':delete')->setName('delete');
$app->get('/convert/{vod}', VodController::class . ':convert')->setName('convert');
$app->get('/download/{vod}', VodController::class . ':download')->setName('download');
$app->get('/troubleshoot/{vod}', VodController::class . ':troubleshoot')->setName('troubleshoot');
$app->get('/check_mute/{vod}', VodController::class . ':check_mute')->setName('check_mute');
$app->get('/render_chat/{vod}', VodController::class . ':render_chat')->setName('render_chat');
$app->get('/fullburn/{vod}', VodController::class . ':fullburn')->setName('fullburn');

$app->get('/hook', HookController::class . ':hook')->setName('hook');
$app->post('/hook', HookController::class . ':hook')->setName('hook_post');

$app->get('/sub', SubController::class . ':sub')->setName('sub');
$app->get('/subs', SubController::class . ':subs')->setName('subs');

$app->get('/api/v0/list', ApiController::class . ':list')->setName('api_list');
$app->get('/api/v0/list_jobs', ApiController::class . ':list_jobs')->setName('api_list_jobs');
$app->get('/api/v0/vod/{vod}', ApiController::class . ':vod')->setName('api_vod');
$app->get('/api/v0/render/menu', ApiController::class . ':render_menu')->setName('api_render_menu');
$app->get('/api/v0/render/streamer/{username}', ApiController::class . ':render_streamer')->setName('api_render_streamer');
$app->get('/api/v0/render/log/[{filename}]', ApiController::class . ':render_log')->setName('api_render_log');
$app->get('/api/v0/check_vods', ApiController::class . ':check_vods')->setName('check_vods');

// $app->get('/dialog/{type}/{text}', DebugController::class . ':dialog')->setName('dialog');

$app->get('/cron/check_deleted_vods', CronController::class . ':check_deleted_vods')->setName('check_deleted_vods');
$app->get('/cron/check_muted_vods', CronController::class . ':check_muted_vods')->setName('check_muted_vods');

$app->get('/tools', ToolsController::class . ':tools')->setName('tools');
$app->post('/tools/fullvodburn', ToolsController::class . ':page_fullvodburn')->setName('tools_fullvodburn');
$app->post('/tools/voddownload', ToolsController::class . ':page_voddownload')->setName('tools_voddownload');
$app->post('/tools/chatdownload', ToolsController::class . ':page_chatdownload')->setName('tools_chatdownload');

// force start recording of streamer
$app->get('/force_record/{username}', function (Request $request, Response $response, array $args) {
    $streams = TwitchHelper::getStreams( TwitchHelper::getChannelId( $args['username'] ) );
    if($streams){
        set_time_limit(0);
        $data = [
            'data' => $streams
        ];
        $TwitchAutomator = new TwitchAutomator();
        $TwitchAutomator->force_record = true;
        $TwitchAutomator->handle( $data );
    }else{
        $response->getBody()->write("No streams found for " . $args['username']);
    }
    return $response;
})->setName('force_record');

// abort recording of streamer
// TODO: refactor
$app->get('/abort_record/{username}', function (Request $request, Response $response, array $args) {

    $username = $args['username'];

    /*
    $vods = glob(TwitchHelper::vod_folder( $args['username'] ) . DIRECTORY_SEPARATOR . $args['username'] . "_*.json");

    foreach ($vods as $k => $v) {

        $vodclass = new App\TwitchVOD();
        $vodclass->load($v);

        $pid = $vodclass->getCapturingStatus();
        if($pid){
            $output = shell_exec("pkill " . escapeshellarg($pid));
            $response->getBody()->write( "<pre>" . $output . "</pre><br>");
        }

    }*/

    $pid = TwitchHelper::getPidfileStatus('capture_' . $username );
    if($pid){
        $output = TwitchHelper::exec( ["kill", $pid] );
        $response->getBody()->write( "Killed process.<br><pre>" . $output . "</pre>");
    }else{
        $response->getBody()->write( "Found no process running for " . $username );
    }

    return $response;
    
})->setName('abort_record');
