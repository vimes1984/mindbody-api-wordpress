<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   mind-body
 * @author    C.J.Churchill <churchill.c.j@gmail.com>
 * @license   GPL-2.0+
 * @link      http://accruesupport.com
 * @copyright 4-29-2014 Accrue
 */
?>
<!-- This file is used to markup the public facing aspect of the plugin. -->
<div ng-controller="calappwidgetside">
	<aside id="sidecal" class="widget widget_text">
		<h3 class="widget-title">Events</h3>			
		<div class="textwidget">
			<span us-spinner="{radius:30, width:8, length: 16}" spinner-key="spinner-1"></span>
			<div ui-calendar="uiConfig.calendar" calendar="myCalendar" ng-model="eventSource" ></div>
		</div>
		<br/>
		<br/>
	</aside>

<div ng-cloak class="event_drop_down_wrap" ng-hide="eventinfoshwhd">
		<div class="eventdropdown row">
			<div id="eventinfo_wrap">
				<div id="eventwraptitle">
					<div class="row">
						<div class="two columns startendtime">
							<h5>{{starttime}} - {{endtime}}</h5>
						</div>
						<div class="ten columns">
							<h5>{{viewevent.title}}</h5>
						</div>
					</div>
				</div>
				<div class="row descevent">
					<div class="twelve columns" ng-bind-html="viewevent.description | unsafe | limitTo:letterLimit"></div>
				</div>
				<div class="row">
					<div class="six columns">
						<a href="{{urllink}}?fromcal=yes" class="btnsevent">Read More</a>
					</div>
					<div class="six columns">
						<p ng-click="closethis()" class="btnsevent">Close Window</p>
					</div>
				</div>
				<!--<div class="eventinfo" ng-repeat="classs in repeatdata | getClasses:calmoment">
					<h2>{{classs.title}}</h2>
					<div ng-bind-html="classs.description | unsafe | limitTo:letterLimit"></div>
				</div>-->
			</div>
				<!-- ngRepeat: offices in data.post_office -->
				<p class="closethis" ng-click="closethis()">x</p>
		</div>
	</div>	
</div>