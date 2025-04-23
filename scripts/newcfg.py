



# Python script to create qobuz-dl config using the frontend settings page. Uses modified code from the official qobuz-dl project


import os
import sys
import configparser
import hashlib
import logging
from bundle import Bundle


config = configparser.ConfigParser()


if os.name == "nt":
    OS_CONFIG = os.environ.get("APPDATA")
else:
    OS_CONFIG = os.path.join(os.environ["HOME"], ".config")


CONFIG_PATH = os.path.join(OS_CONFIG, "qobuz-dl")   # {OS}/{CONFIG FOL}/qobuz-dl
CONFIG_FILE = os.path.join(CONFIG_PATH, "config.ini") # {OS}/{CONFIG FOL}/qobuz-dl/config.ini
QOBUZ_DB = os.path.join(CONFIG_PATH, "qobuz_dl.db") # {OS}/{CONFIG FOL}/qobuz-dl/qobuz_dl.db


DEFAULT_FOLDER_FORMAT = "{artist} - {album} ({year}) [{bit_depth}B-{sampling_rate}kHz]"
DEFAULT_TRACK = "{tracknumber}. {tracktitle}"


EMAIL = ''
PASSWORD = ''
DEFAULT_FOLDER = 'Qobuz Downloads'
QUALITY = 6



# if qobuz-dl config directory does not exist, make one.
if not os.path.isdir(CONFIG_PATH) or not os.path.isfile(CONFIG_FILE):
    os.makedirs(CONFIG_PATH, exist_ok=True)




# all four arguments are missing
if len(sys.argv) < 5:
    print('\n',str(sys.argv))
    print('Missing arguments. Exiting...')
    sys.exit()


# Email address argument is missing
if not sys.argv[1] or sys.argv[1] == '':
    print('Missing argument. Exiting...')
    sys.exit()
else:
    EMAIL = sys.argv[1]


# Password argument is missing
if not sys.argv[2] or sys.argv[2] == '':
    print('Missing argument. Exiting...')
    sys.exit()
else:
    PASSWORD = sys.argv[2]

# Downloads folder name argument is missing
if not sys.argv[3] or sys.argv[3] == '':
    DEFAULT_FOLDER = 'Qobuz Downloads'
    print('Missing argument. Setting DEFAULT_FOLDER to "Qobuz Downloads"...')
else:
    DEFAULT_FOLDER = sys.argv[3]


# Audio quality value argument is missing
if not sys.argv[4] or sys.argv[4] == '':
    print('Missing argument. Setting QUALITY to 6...')
    QUALITY = 6
else:
    QUALITY = sys.argv[4]




config["DEFAULT"]["email"] = EMAIL
config["DEFAULT"]["password"] = hashlib.md5(PASSWORD.encode("utf-8")).hexdigest()
config["DEFAULT"]["default_folder"] = DEFAULT_FOLDER
config["DEFAULT"]["default_quality"] = QUALITY


config["DEFAULT"]["default_limit"] = "20"
config["DEFAULT"]["no_m3u"] = "false"
config["DEFAULT"]["albums_only"] = "false"
config["DEFAULT"]["no_fallback"] = "false"
config["DEFAULT"]["og_cover"] = "false"
config["DEFAULT"]["embed_art"] = "false"
config["DEFAULT"]["no_cover"] = "false"
config["DEFAULT"]["no_database"] = "false"

logging.info(f"Getting tokens. Please wait...")

bundle = Bundle()
config["DEFAULT"]["app_id"] = str(bundle.get_app_id())
config["DEFAULT"]["secrets"] = ",".join(bundle.get_secrets().values())
config["DEFAULT"]["folder_format"] = DEFAULT_FOLDER_FORMAT
config["DEFAULT"]["track_format"] = DEFAULT_TRACK
config["DEFAULT"]["smart_discography"] = "false"

with open(CONFIG_FILE, "w") as configfile:
    config.write(configfile)
logging.info(
    f"Config file updated."
)