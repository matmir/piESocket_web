#!/bin/sh

echo -n 'openNetworkHMI:'
systemctl is-active openNetworkHMI.service

echo -n 'Apache2:'
systemctl is-active apache2.service

echo -n 'MySQL:'
systemctl is-active mysql.service

echo -n 'AutoloadONH:'
test -e /etc/systemd/system/multi-user.target.wants/openNetworkHMI.service && echo "ok" || echo "nok"