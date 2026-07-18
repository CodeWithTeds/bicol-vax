SMTP setup for Gmail (use app password)

Add the following to your `.env` (replace values if needed):

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=bicolvaxclinic@gmail.com
MAIL_PASSWORD=xtygzazrbrnrkhzw
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=bicolvaxclinic@gmail.com
MAIL_FROM_NAME="BicolVax Clinic"

Notes:
- The above `MAIL_PASSWORD` is the Gmail App Password (remove spaces if provided).
- Do NOT commit real credentials to source control. Keep them in your local `.env` only.
