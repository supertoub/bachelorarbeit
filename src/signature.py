import email
import base64
import pandas as pd

df = pd.DataFrame(columns=['body'])
for i in range(1, 100):
    msg = email.message_from_file(open('../data/'+str(i)+'.eml'))
    body = ''
    if msg.is_multipart():
        for payload in msg.get_payload():
            if 'text/plain' in payload['content-type']:
                if('base64' in payload['Content-Transfer-Encoding']):
                    body = base64.b64decode(payload.get_payload())
                else:
                    body = payload.get_payload()
    else:
         body = msg.get_payload()
    df = df.append({'body': str(body)}, ignore_index=True)

print(df.to_csv())