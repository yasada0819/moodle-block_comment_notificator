# Comment Notificator block

## 日本語

### 概要

Comment Notificator は、Moodle のコースに設置して使うブロックプラグインです。

データベース、用語集、課題提出コメントに新しいコメントが投稿されたとき、エントリや提出の作成者、同じ対象にコメントした他のユーザ、またはコースの教師へ通知できます。

このプラグインは `local_db_com_notificator` のコース単位版として作られています。Moodle 全体へ一律に適用するのではなく、ブロックを設置したコースごとに通知対象を調整できます。

### 対応しているコメント

- データベースエントリへのコメント
- 用語集エントリへのコメント
- 課題提出コメント

### 対応していないコメント

- 問題バンクのコメント
- Wiki のコメント

問題バンクのコメント通知が必要な場合は、既存の `local_db_com_notificator` を利用してください。

### サイト管理設定

サイト管理のプラグイン設定で、通知モードを選択できます。

- 無効: 通知を送信しません。
- サイト全体: ブロックの設置状況に関係なく、サイト全体で通知します。
- ブロックで制御: ブロックが設置され、設定されたコースだけで通知します。

サイト全体モードでは、データベース、用語集、課題のどの活動タイプを通知対象にするかを選択できます。また、連続通知抑制と、他のコメント者への通知を設定できます。

### ブロック設定

ブロックをコースに追加すると、そのコース内で次の項目を設定できます。

- このコースで通知を有効にするか
- 通知対象にする活動タイプ
- コース内の対応活動すべてに適用するか、選択した活動だけに適用するか
- エントリまたは提出の作成者へ通知するか
- 他のコメント者へ通知するか
- コースの教師へ通知するか

「活動タイプ」は複数選択できます。例えば、データベースだけ、課題だけ、データベースと用語集だけ、といった指定ができます。

### インストール

1. `block_comment_notificator` フォルダを Moodle の `blocks` ディレクトリに配置します。
2. Moodle の通知ページにアクセスして、プラグインのインストールを完了します。
3. サイト管理で通知モードを設定します。
4. ブロックで制御する場合は、対象コースに Comment Notificator ブロックを追加して設定します。

### 補足

このプラグインはブロックプラグインなので、コース単位の運用に向いています。サイト全体に広く効かせたい場合や、問題バンクコメント通知を使いたい場合は、Local プラグイン版との併用または使い分けを検討してください。

## English

### Overview

Comment Notificator is a Moodle block plugin that can be added to courses.

When a new comment is posted to a database entry, glossary entry, or assignment submission, the plugin can notify the entry or submission owner, other commenters on the same item, and/or course teachers.

This plugin is designed as a course-scoped alternative to `local_db_com_notificator`. Instead of applying the same notification behavior across the whole Moodle site, it lets each course control which activities should send notifications.

### Supported comments

- Comments on database entries
- Comments on glossary entries
- Assignment submission comments

### Not supported

- Question bank comments
- Wiki comments

If your site needs Question bank comment notifications, keep using `local_db_com_notificator`.

### Site administration settings

The plugin has three notification modes.

- Disabled: no notifications are sent.
- Global: notifications are sent site-wide, regardless of block placement.
- Block controlled: notifications are sent only for courses where this block is added and configured.

In global mode, you can choose which activity types are enabled: Database, Glossary, and Assignment. You can also configure repeated notification suppression and notifications to other commenters.

### Block settings

After adding the block to a course, you can configure:

- Whether notifications are enabled in the course
- Which activity types are monitored
- Whether all supported activities in the course are monitored, or only selected activities
- Whether the entry or submission owner is notified
- Whether other commenters are notified
- Whether course teachers are notified

The activity type setting supports multiple selections. For example, you can monitor only databases, only assignments, or databases and glossaries together.

### Installation

1. Place the `block_comment_notificator` folder in the Moodle `blocks` directory.
2. Visit the Moodle notifications page to complete installation.
3. Configure the notification mode in site administration.
4. If using block controlled mode, add the Comment Notificator block to the target courses and configure each block instance.

### Notes

Because this is a block plugin, it is best suited to course-level notification control. If you need broad site-wide behavior or Question bank comment notifications, consider using it alongside, or instead of, the Local plugin version.
