ARQUIVO_TMP="/tmp/arquivos-hmw-$$.txt"
 
find . -type f -name \*.php > $ARQUIVO_TMP
xgettext -k_e -k__ -L PHP --from-code utf-8 --no-wrap -d dashboard -o dashboard.pot -f $ARQUIVO_TMP
 
#rm -f $ARQUIVO_TMP
#msginit -l pt_BR --no-wrap --no-translator -o i18n/pt_BR/LC_MESSAGES/dashboard.po -i i18n/dashboard.pot