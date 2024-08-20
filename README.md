# Joomla 3 EOL Security Fixes
 
This plugin will help you update the files associated with the known security fixes as listed below.
It will overwrite the files and then auto uninstalls itself again. 
## Version 1.0.6 fixes the below security issues and bugs caused by it. (it also contains all previous versions fixes)
- [20240805] - Core - XSS vectors in Outputfilter::strip* methods. More info on the vulnerability here: https://developer.joomla.org/security-centre/946-20240805-core-xss-vectors-in-outputfilter-strip-methods.html
- [20240802] - Core - Cache Poisoning in Pagination. More info on the vulnerability here: https://developer.joomla.org/security-centre/942-20240802-core-cache-poisoning-in-pagination.html
- [20240801] - Core - Inadequate validation of internal URLs. More info on the vulnerability here: https://developer.joomla.org/security-centre/941-20240801-core-inadequate-validation-of-internal-urls.html
- Besides the security fixes i also fixed the com_search and com_finder views that were broken because of the security fixes.

## Version 1.0.5 fixes the below security issues. (it also contains all previous versions fixes)
- [20240705] - Core - XSS in com_fields default field value. More info on the vulnerability here: https://developer.joomla.org/security-centre/939-20240705-core-xss-in-com-fields-default-field-value.html
- [20240704] - Core - XSS in Wrapper extensions. More info on the vulnerability here: https://developer.joomla.org/security-centre/938-20240704-core-xss-in-wrapper-extensions.html
- [20240703] - Core - XSS in StringHelper::truncate method. More info on the vulnerability here: https://developer.joomla.org/security-centre/937-20240703-core-xss-in-stringhelper-truncate-method.html

## Version 1.0.0 - 1.0.4 fixes the below security issues.
- [20240205] - Core - Inadequate content filtering within the filter code. More info on the vulnerability here: https://developer.joomla.org/security-centre/929-20240205-core-inadequate-content-filtering-within-the-filter-code.html
- [20240203] - Core - XSS in media selection fields. More info on the vulnerability here: https://developer.joomla.org/security-centre/927-20240203-core-xss-in-media-selection-fields.html
- [20240202] - Core - Open redirect in installation application. More info on the vulnerability here: https://developer.joomla.org/security-centre/926-20240202-core-open-redirect-in-installation-application.html
- [20240201] - Core - Insufficient session expiration in MFA management views. More info on the vulnerability here: https://developer.joomla.org/security-centre/925-20240201-core-insufficient-session-expiration-in-mfa-management-views.html
- [20231101] - Core - Exposure of environment variables. More info on the vulnerability here: https://developer.joomla.org/security-centre/919-20231101-core-exposure-of-environment-variables.html

## Donate to the joomla project!
If this plugin saved you valuable time please consider donating something to the joomla project: https://community.joomla.org/donate. 
Especially agencies who will save tons of time when they have multiple websites still on J3. Any donation is much appreciated.

## Backup First!
**Always try this fix first on a test environment!**
- The fix for security issue [20231101] could potentially break language files that were not following exact specification. Previously these language files would still work but in fixing the vulnerability the strictness of how these files are processed makes it that a language string can not have new lines in the content anymore.

