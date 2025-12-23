<?php

add_shortcode('vtc_agenda_fullscreen_yodeck', 'vtc_agenda_fullscreen_yodeck');
function vtc_agenda_fullscreen_yodeck($atts = [], $content = null) {
	//var_dump(json_encode(tribe_get_events(array('ends_after' => 'now'))));
	$months = ["jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec"];
	$html = "";
	$events = tribe_get_events(array('ends_after' => 'now', 'posts_per_page' => 5));
	
	if (empty($events)) {
		$html = "<div class='vtc__event__title'><h1>Geen evenementen gepland</h1></div>";		
	}
	
	foreach ($events as $event) {
		date_default_timezone_set('Europe/Amsterdam');
		$event_date = date_create($event->event_date_utc);
		$event_date->setTimeZone(new DateTimeZone('Europe/Amsterdam'));
		
		$tribe_event = tribe_get_event($event);
		
		$start_date = date_create($tribe_event->start_date);
		$end_date = date_create($tribe_event->end_date);
		
		// For some reason it is not possible to get the month beginning with 0
		$event_start_month_num = $start_date->format('n') - 1;
		$event_end_date_month_num = $end_date->format('n') - 1;
		
		$event_year = $start_date->format('Y');
		
		$event_start_day = $start_date->format('d');
		$event_start_time = $start_date->format('H:i');
		
		$event_end_day = $end_date->format('d');
		$event_end_time = $end_date->format('H:i');
		
		$time = $event_start_time . " : " . $event_end_time;
		
		if ($tribe_event->all_day) {
			$time = "";
		}
		
		$event_html = <<<HTML
		<a class="vtc__event" href="{$event->guid}">
			<div class="vtc__event--date">
				<div class="vtc__event--month">{$months[$event_start_month_num]}</div>
				<div class="vtc__event--day">{$event_start_day}</div>
			</div>
HTML;
		
		// Alleen een extra datum neerzetten als het een multiday is, en als er meer dan een dag tussen zit.
		// Dit om te voorkomen dat als er iets midden in de nacht is afgelopen, dit twee datums krijgt
		if ($tribe_event->multiday && ($start_date->diff($end_date))->d > 0) {
			$event_html .= <<<HTML
				<div class="vtc__event--divider"></div>
				<div class="vtc__event--date">
					<div class="vtc__event--month">{$months[$event_end_date_month_num]}</div>
					<div class="vtc__event--day">{$event_end_day}</div>
				</div>
HTML;
		}
		
		$event_html .= <<<HTML
			<div class="vtc__event--content">
				<h2>{$event->post_title}</h2>
				<p>{$time}</p>
				<p>{$event->post_excerpt}</p>
			</div>
		</a>
HTML;
		$html .= $event_html;
	}
	
	return $html;
}


