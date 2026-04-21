@props([
    'title' => null,
    'headerTitle' => null,
    'headerSubtitle' => null,
    'sidebarModules' => null,
])

@include('layouts.manager-shell', [
    'title' => $title,
    'headerTitle' => $headerTitle,
    'headerSubtitle' => $headerSubtitle,
    'sidebarModules' => $sidebarModules,
    'slot' => $slot,
])

