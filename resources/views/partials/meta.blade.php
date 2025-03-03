<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="author" content="{{ env('WEB_AUTHOR') }}">
<meta name="description" content="{{ env('SITE_DESCRIPTION') }}">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="keywords" content="{{ env('SITE_KEYWORDS') }}">
<meta name="robots" content="index, follow">
<meta property="og:title" content="{{ env('OG_TITLE') }}">
<meta property="og:description" content="{{ env('SITE_DESCRIPTION') }}">
<meta property="og:image" content="{{ env('OG_IMAGE') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="google-site-verification" content="{{ env('GOOGLE_SITE_VERIFICATION') }}">
<title>{{ env('APP_NAME') }}</title>