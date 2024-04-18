<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
//import '@vuepic/vue-datepicker/dist/main.css';

const dates = defineModel()
const focused = defineModel('focused')
</script>

<template>
	<VueDatePicker v-bind="$attrs"
		v-model="dates"
		format="d. MMM yy"
		:enable-time-picker="false"
		range

		auto-apply
		six-weeks="center"

		@focus="focused = true"
		@blur="focused = false"
	>
	</VueDatePicker>
</template>

<style lang="scss">
@import "@vuepic/vue-datepicker/dist/main.css";
/*
$text-field-affix-color
rgba(var(--v-theme-on-surface), var(--v-medium-emphasis-opacity))
$text-field-border-radius
settings.$border-radius-root
$text-field-details-padding-inline
16px
$text-field-disabled-affix-color
rgba(var(--v-theme-on-surface), var(--v-disabled-opacity))
$text-field-input-flex
1
$text-field-input-padding-end
0
$text-field-input-padding-start
6px
$text-field-input-transition

color:            var(--v-primary-base);
background-color: var(--v-accent-lighten2);
*/

.dp__theme_light {
	--dp-background-color: var($text-field-affix-color);
	--dp-text-color: #212121;
	--dp-hover-color: #f3f3f3;
	--dp-hover-text-color: #212121;
	--dp-hover-icon-color: #959595;
	--dp-primary-color: #1976d2;
	--dp-primary-disabled-color: #6bacea;
	--dp-primary-text-color: #f8f5f5;
	--dp-secondary-color: #c0c4cc;
	--dp-border-color: #ddd;
	--dp-menu-border-color: #ddd;
	--dp-border-color-hover: #aaaeb7;
	--dp-disabled-color: #f6f6f6;
	--dp-scroll-bar-background: #f3f3f3;
	--dp-scroll-bar-color: #959595;
	--dp-success-color: #76d275;
	--dp-success-color-disabled: #a3d9b1;
	--dp-icon-color: #959595;
	--dp-danger-color: #ff6f60;
	--dp-marker-color: #ff6f60;
	--dp-tooltip-color: #fafafa;
	--dp-disabled-color-text: #8e8e8e;
	--dp-highlight-color: rgb(25 118 210 / 10%);
	--dp-range-between-dates-background-color: var(--dp-hover-color, #f3f3f3);
	--dp-range-between-dates-text-color: var(--dp-hover-text-color, #212121);
	--dp-range-between-border-color: var(--dp-hover-color, #f3f3f3);
}

:root {
	/*General*/
	--dp-font-family: -apple-system, blinkmacsystemfont, "Segoe UI", roboto, oxygen, ubuntu, cantarell, "Open Sans",
	"Helvetica Neue", sans-serif;
	--dp-border-radius: var($text-field-border-radius); /*Configurable border-radius*/
	--dp-cell-border-radius: 4px; /*Specific border radius for the calendar cell*/
	--dp-common-transition: all 0.1s ease-in; /*Generic transition applied on buttons and calendar cells*/

	/*Sizing*/
	--dp-button-height: 35px; /*Size for buttons in overlays*/
	--dp-month-year-row-height: 35px; /*Height of the month-year select row*/
	--dp-month-year-row-button-size: 35px; /*Specific height for the next/previous buttons*/
	--dp-button-icon-height: 20px; /*Icon sizing in buttons*/
	--dp-cell-size: 35px; /*Width and height of calendar cell*/
	--dp-cell-padding: 5px; /*Padding in the cell*/
	--dp-common-padding: 10px; /*Common padding used*/
	--dp-input-icon-padding: 35px; /*Padding on the left side of the input if icon is present*/
	--dp-input-padding: 6px 30px 6px 12px; /*Padding in the input*/
	--dp-menu-min-width: 260px; /*Adjust the min width of the menu*/
	--dp-action-buttons-padding: 2px 5px; /*Adjust padding for the action buttons in action row*/
	--dp-row-margin:  5px 0; /*Adjust the spacing between rows in the calendar*/
	--dp-calendar-header-cell-padding:  0.5rem; /*Adjust padding in calendar header cells*/
	--dp-two-calendars-spacing:  10px; /*Space between multiple calendars*/
	--dp-overlay-col-padding:  3px; /*Padding in the overlay column*/
	--dp-time-inc-dec-button-size:  32px; /*Sizing for arrow buttons in the time picker*/
	--dp-menu-padding: 6px 8px; /*Menu padding*/

	/*Font sizes*/
	--dp-font-size: 1rem; /*Default font-size*/
	--dp-preview-font-size: 0.8rem; /*Font size of the date preview in the action row*/
	--dp-time-font-size: 0.8rem; /*Font size in the time picker*/

	/*Transitions*/
	--dp-animation-duration: 0.1s; /*Transition duration*/
	--dp-menu-appear-transition-timing: cubic-bezier(.4, 0, 1, 1); /*Timing on menu appear animation*/
	--dp-transition-timing: ease-out; /*Timing on slide animations*/
}
</style>