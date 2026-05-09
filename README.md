# Comment Notificator block

Course block plugin for Moodle that sends notifications when comments are added to selected activities.

This block is intended as a course-scoped alternative to `local_db_com_notificator`.

## Initial scope

- Database comments
- Glossary comments
- Assignment submission comments

Question bank and Wiki comments are intentionally out of scope for this block version. Sites that need Question bank notifications can keep using the local plugin.

## Modes

- Disabled: no notifications are sent.
- Global: notifications are sent site-wide for the enabled activity types.
- Block controlled: notifications are sent only in courses where this block is configured.
