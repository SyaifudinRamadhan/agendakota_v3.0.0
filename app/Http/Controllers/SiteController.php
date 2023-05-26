<?php

namespace App\Http\Controllers;

use File;
use Storage;
use App\Models\Event;
use App\Models\EventSite as Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function create($organizationID, $eventID, Request $request) {
        $check = Site::where('event_id', $eventID)->get('id');
        if ($check->count() > 0) {
            return redirect()->back();
        }

        $this->createConfigFile($request->domain);
        die;

        $event = Event::where('id', $eventID)->first();

        $saveData = Site::create([
            'event_id' => $eventID,
            'domain' => $request->domain,
            'template' => 'kerenbanget',
            'site_title' => $event->name,
            'tagline' => $event->name,
            'meta_description' => $event->description,
            'meta_keyword' => strtolower($event->category)
        ]);
        
        return redirect()->route('organization.event.site', [$organizationID, $eventID])->with([
            'message' => "Landing site has been created"
        ]);
    }
    public function update($organizationID, $eventID, Request $request) {
        $data = Site::where('event_id', $eventID);
        $toUpdate = [
            'site_title' => $request->site_title,
            'domain' => $request->domain,
            'tagline' => $request->tagline,
            'meta_description' => $request->meta_description,
            'meta_keyword' => $request->meta_keyword,
        ];
        $updateData = $data->update($toUpdate);

        return redirect()->route('organization.event.site', [$organizationID, $eventID])->with([
            'message' => "Landing data has been updated"
        ]);
    }

    public function createConfigFile($domain) {
        // $domain = "marcom.id";
        $filename = $domain.".conf";
        $filepath = public_path("site-config/".$domain.".conf");
        $content = "<VirtualHost *:80>
        ServerName ".$domain."

        ServerAdmin halo@agendakota.id
        DocumentRoot /var/www/explore/ak_landing/public

        ErrorLog $\{APACHE_LOG_DIR}/error.log_".str_replace('.', '', $domain)."
        CustomLog $\{APACHE_LOG_DIR}/access.log_".str_replace('.', '', $domain)." combined

RewriteEngine on
RewriteCond %{SERVER_NAME} =".$domain."
RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>";
        // return $filepath;
        $create = File::put($filepath, $content);
        $hasMoved = false;
        if ($create && File::exists($filepath)) {
            $hasMoved = true;
            $target = '/etc/apache2/sites-available/' . $filename;
            $moveToApacheSites = File::move($filepath, $target);
        }
        var_dump($hasMoved);
    }
}
