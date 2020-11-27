```
Contributors: faqihamruddin.com
Author URL: https://faqihamruddin.com
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Requires PHP: 5.6.20
```
Wordpress Lead Generator Form

### Lead Gen Form

A plugin to create form on wordpress front end

### Installation

1. Download this zip file
2. Extract and upload extracted folder to plugin directory ```wp-content/plugins```
3. Activate Lead Gen Form plugin
4. Add shortcode ```LGF Shortcode``` to any wordpress editor

![alt text](https://github.com/frasamaya/lead-gen-form/blob/main/shortcode.png?raw=true)

### Shortcode
Shortcode description:
name is mandatory and cannot be change
label is changable to everything you need
required is changable to ```true``` or ```false```
maxlength is changable to limit textfield length
rows is changable to set textarea row

```
[lgf_shortcode]
	[lgf_field name="lgf_name" label="Name" required="true" maxlength="-1"]
	[lgf_field name="lgf_phone" label="Phone Number" required="true" maxlength="-1"]
	[lgf_field name="lgf_email" label="Email Address" required="true" maxlength="-1"]
	[lgf_field name="lgf_budget" label="Desired Budget" required="true" maxlength="-1"]
	[lgf_field name="lgf_duration" label="Expected Project Duration" required="true" maxlength="-1"]
	[lgf_field name="lgf_references" label="Project Reference" required="true" maxlength="-1"]
	[lgf_field name="lgf_message" label="Message" required="true" maxlength="-1" rows="3"]
[/lgf_shortcode]
```

