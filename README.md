# Joomla 3 EOL Security Fixes 
This plugin will help you update the files associated with the known security fixes as listed below.
It will overwrite the files and then auto uninstalls itself again.

## Version 1.1.2 fixes the below update issues (it also contains all previous versions fixes)
- Infrastructure — Realigned the internal XML manifest and installer scripts to v1.1.2 to force a clean update path. This ensures the Joomla updater successfully applies the files, updates the success messages, and correctly displays the new version in the backend footer.

## Version 1.0.11 fixes the below security issues (it also contains all previous versions fixes)
- [20260501] — Core — XSS in feed modules (CVE-2026-25900). More info: https://developer.joomla.org/security-centre/1033-20260501-core-xss-in-feed-modules.html
- [20260503] — Core — XSS in com_contenthistory (CVE-2026-30894). More info: https://developer.joomla.org/security-centre/1035-20260503-core-xss-in-com-contenthistory
- [20260509] — Core — LFI in HTMLView layout parameter (CVE-2026-40383). More info: https://developer.joomla.org/security-centre/1041-20260509-core-lfi-in-htmlview-layout-parameter.html
- [20260518] — Core — Transport encryption downgrade for password and username reset links (CVE-2026-48902). More info: https://developer.joomla.org/security-centre/1050-20260518-core-transport-encryption-downgrade-for-password-and-username-reset-links.html
- [20260519] — Framework — Inadequate content filtering within the checkAttribute filter code (CVE-2026-48903). More info: https://developer.joomla.org/security-centre/1051-20260519-framework-inadequate-content-filtering-within-the-checkattribute-filter-code.html
- [20260520] — Framework — Inadequate content filtering within the cleanAttributes filter code (CVE-2026-48905). More info: https://developer.joomla.org/security-centre/1052-20260520-framework-inadequate-content-filtering-within-the-cleanattributes-filter-code.html

## Version 1.0.10 fixes the below security issues (it also contains all previous versions fixes)
- [20260301] — Core — ACL hardening in com_ajax (CVE-2026-21629). More info: https://developer.joomla.org/security-centre/1018-20260301-core-acl-hardening-in-com_ajax.html
- [20260303] — Core — XSS vector in com_associations comparison view (CVE-2026-21631). More info: https://developer.joomla.org/security-centre/1020-20260303-core-xss-vector-in-com_associations.html

## Version 1.0.9 fixes the below security issues (it also contains all previous versions fixes)
- [20260102] — Core — XSS vector in the pagebreak plugin (CVE-2025-63083). More info: https://developer.joomla.org/security-centre/1017-20260102-core-xss-vector-in-the-pagebreak-plugin.html
- [20260101] — Core — Inadequate content filtering for data URLs (CVE-2025-63082). More info: https://developer.joomla.org/security-centre/1016-20260101-core-inadequate-content-filtering-for-data-urls.html
- [20250401] — Framework — SQL injection in Database package (CVE-2025-25226). More info: https://developer.joomla.org/security-centre/963-20250401-framework-sql-injection-vulnerability-in-quotenamestr-method-of-database-package.html
- [20250301] — Core — Malicious file uploads via Media Manager (CVE-2025-22213). More info: https://developer.joomla.org/security-centre/961-20250301-core-malicious-file-uploads-via-media-manager.html
- [20240702] — Core — Lack of escaping in module chrome attributes (CVE-2024-40747). More info: https://developer.joomla.org/security-centre/941-20240702-core-lack-of-escaping-in-module-chrome-attributes.html

## Version 1.0.8 fixes the below security issues and bugs caused by it. (it also contains all previous versions fixes)
- [20250103] - Core - Read ACL violation in multiple core views. More info on the vulnerability here: https://developer.joomla.org/security-centre/956-20250103-core-read-acl-violation-in-multiple-core-views.html
- [20250102] - Core - XSS vector in the id attribute of menu lists. More info on the vulnerability here: https://developer.joomla.org/security-centre/955-20250102-core-xss-vector-in-the-id-attribute-of-menu-lists.html

## Version 1.0.6 fixes the below security issues and bugs caused by it. (it also contains all previous versions fixes)
- [20240805] - Core - XSS vectors in Outputfilter::strip* methods. More info on the vulnerability here: https://developer.joomla.org/security-centre/946-20240805-core-xss-vectors-in-outputfilter-strip-methods.html
- [20240802] - Core - Cache Poisoning in Pagination. More info on the vulnerability here: https://developer.joomla.org/security-centre/942-20240802-core-cache-poisoning-in-pagination.html
- [20240801] - Core - Inadequate validation of internal URLs. More info on the vulnerability here: https://developer.joomla.org/security-centre/941-20240801-core-inadequate-validation-of-internal-urls.html
- Besides the security fixes i also fixed the com_search and com_finder views that were broken because of the security fixes. Thanks to David Jardin for providing the fixes for J4/5. This way i was able to backport them to J3.

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
- The fix for security issue [20240801] could potentially break components that worked in certain way. More info about this here: https://docs.joomla.org/J5.x:Behavior_change_for_Uri::isInternal_for_URLs_without_protocol

**A special thank you to the Joomla Security Strike Team for keeping Joomla save and doing the hard work here. I just backported their fixes!**
