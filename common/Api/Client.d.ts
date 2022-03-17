import { PHPDateTimeJSON } from "../PHPDateTime";
import { MediaInfo, MediaInfoPublic } from "../mediainfofield";
import { SubStatus, VideoQuality } from "../Config";
import { ChannelData } from "../Channel";
import { MuteStatus, ExistStatus } from "../../common/Defs";

export type ApiVodSegment = {
    basename: string;
    filesize: number;
    deleted: boolean;
};

export type ApiVodChapter = {
    title: string;
    
    game_id?: string;
    game_name?: string;
    game?: ApiGame;
    box_art_url?: string;

    strings: Record<string, string>;
    duration: number;
    
    started_at: string;
    // datetime: PHPDateTimeJSON;
    offset: number;
    viewer_count?: number;
    // width: number; // why
    is_mature: boolean;
};

export type ApiGame = {
    id: string;
    name: string;
    game_name: string;
    box_art_url: string;
    favourite: boolean;
    image_url: string;
    added: number;
};

export type ApiVod = {
    basename: string;

    stream_title: string;
    stream_resolution?: VideoQuality;

    segments: ApiVodSegment[];
    segments_raw: string[];

    streamer_name: string;
    streamer_id: string;
    streamer_login: string;

    twitch_vod_duration?: number;
    twitch_vod_muted?: MuteStatus;
    twitch_vod_status?: ExistStatus;
    twitch_vod_id?: string;
    twitch_vod_date?: string;
    twitch_vod_title?: string;
    
    twitch_vod_neversaved?: boolean;
    twitch_vod_exists?: boolean;
    twitch_vod_attempted?: boolean;

    saved_at?: string;
    started_at: string;
    ended_at?: string;
    capture_started?: string;
    conversion_started?: string;

    is_capturing: boolean;
    is_converting: boolean;
    is_converted: boolean;
    is_finalized: boolean;

    is_chat_downloaded: boolean;
    is_chatdump_captured: boolean;
    is_chat_rendered: boolean;
    is_chat_burned: boolean;
    is_vod_downloaded: boolean;
    is_capture_paused: boolean;
    is_lossless_cut_generated: boolean;

    api_hasFavouriteGame: boolean;
    api_getUniqueGames: ApiGame[];
    api_getWebhookDuration?: string;
    api_getDuration: number | null;
    api_getCapturingStatus: number | false;
    api_getConvertingStatus: number | false;
    api_getRecordingSize: number | false;
    api_getChatDumpStatus: number | false;
    api_getDurationLive: number | false;

    path_chat: string;
    path_downloaded_vod: string;
    path_losslesscut: string;
    path_chatrender: string;
    path_chatburn: string;
    path_chatdump: string;
    path_chatmask: string;
    path_adbreak: string;
    path_playlist: string;

    duration_live: number | false;
    duration: number;

    total_size: number;

    // game_offset: number;

    // video_metadata: MediaInfo;
    video_metadata_public?: MediaInfoPublic;

    chapters: ApiVodChapter[];

    webpath: string;
};

export type ApiSettingsField = {
    key: string;
    group: string;
    text: string;
    type: string;
    default?: any;
    required: boolean;
    help: string;
    choices?: string[];
    deprecated?: boolean;
    pattern?: string;
};

/*
export interface ApiConfig {
    api_client_id: string;
    api_secret: string;
    app_name: string;
    app_url: string;
    app_verbose: boolean;
    basepath: string;
    bin_dir: string;
    burn_crf: number;
    burn_preset: string[];
    channel_folders: boolean;
    chat_compress: boolean;
    chat_dump: boolean;
    debug: boolean;
    disable_ads: boolean;
    download_retries: number;
    encode_audio: boolean;
    favourites: Record<string, string>;
    ffmpeg_path: string;
    fix_corruption: boolean;
    hls_timeout: number;
    hook_callback: string;
    instance_id: string;
    low_latency: boolean;
    mediainfo_path: string;
    password_secure: boolean;
    password: string;
    pipenv_enabled: boolean;
    playlist_dump: boolean;
    process_wait_method: number;
    relative_time: boolean;
    storage_per_streamer: number;
    sub_lease: number;
    timezone: string[];
    ts_sync: boolean;
    twitchdownloader_path: string;
    vod_container: string[];
    vods_to_keep: number;
    webhook_url: string;
    websocket_client_address: string;
    websocket_enabled: boolean;
    websocket_server_address: string;
    youtube_api_client_id: string;
}
*/

export type ApiChannel = {
    userid: string;

    /** @deprecated */
    // username: string;

    display_name: string;
    login: string;
    description: string;
    quality: VideoQuality[] | undefined;
    
    vods_raw: string[];
    vods_list: ApiVod[];
    vods_size: number;

    
    is_live: boolean;
    is_converting: boolean;
    profile_image_url: string;

    subbed_at?: string;
    expires_at?: string;
    last_online?: string;

    match: string[] | undefined;

    download_chat: boolean;
    no_capture: boolean;
    burn_chat: boolean;

    current_chapter?: ApiVodChapter;
    current_game?: ApiGame;
    current_vod?: ApiVod;
    // channel_data: {
    //     profile_image_url: string;
    // };
    channel_data: ChannelData | undefined;

    // api_getSubscriptionStatus: SubStatus;
    api_getSubscriptionStatus: boolean;
};

export type ApiSubscription = {
    type: string;
    id: string;
    username: string;
    user_id: string;
    callback: string;
    instance_match: boolean;
    status: string;
    created_at: string;
};

export type ApiChannelConfig = {
    login: string;
    match: string[];
};

export type ApiJob = {
    name: string;
    pid: number;
    status: number;
};

export type ApiLogLine = {
    level: string;
    module: string;
    date_string: string;
    date: number; // unix timestamp in ms
    text: string;
    metadata?: any;
};