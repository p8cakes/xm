import os;

''' Update the following to actual values '''

dbServerName = "";
dbName = "";
dbUsername = "";
dbPassword = "";

firstName = "";
lastName = "";
yourEmailAddress = "";

fqUri = "";
cookieQualifier = "";

with open("functions.php", "wt") as fout:
    with open("functions.original.php", "rt") as fin:
        for line in fin:

            nextLine = line;

            if ("$$DATABASE_SERVER$$" in line)                  : nextLine = nextLine.replace("$$DATABASE_SERVER$$", dbServerName);
            if ("$$DATABASE_NAME$$" in line)                    : nextLine = nextLine.replace("$$DATABASE_NAME$$", dbName);
            if ("$$DB_USERNAME$$" in line)                      : nextLine = nextLine.replace("$$DB_USERNAME$$", dbUsername);
            if ("$$DB_PASSWORD$$" in line)                      : nextLine = nextLine.replace("$$DB_PASSWORD$$", dbPassword);
            if ("$$SITE_URL$$" in line)                         : nextLine = nextLine.replace("$$SITE_URL$$", fqUri);
            if ("$$COOKIE_QUALIFIER$$" in line)                 : nextLine = nextLine.replace("$$COOKIE_QUALIFIER$$", cookieQualifier);

            fout.write(nextLine);

with open("xm.sql", "wt") as fout:
    with open("xm.original.sql", "rt") as fin:
        for line in fin:

            nextLine = line;

            if ("$$DATABASE_NAME$$" in line)                    : nextLine = nextLine.replace("$$DATABASE_NAME$$", dbName);
            if ("$$FIRST_NAME$$" in line)                       : nextLine = nextLine.replace("$$FIRST_NAME$$", firstName);
            if ("$$LAST_NAME$$" in line)                        : nextLine = nextLine.replace("$$LAST_NAME$$", lastName);
            if ("$$YOUR_EMAIL_ADDRESS$$" in line)               : nextLine = nextLine.replace("$$YOUR_EMAIL_ADDRESS$$", yourEmailAddress);

            ''' Comment out the next line only if you know what you're doing! '''
            if ("-- drop database" in line)                     : nextLine = nextLine.replace("-- drop database", "drop database");

            fout.write(nextLine);

os.chdir("_static");

with open("scripts.js", "wt") as fout:
    with open("scripts.original.js", "rt") as fin:
        for line in fin:

            nextLine = line;

            if ("$$SITE_URL$$" in line)                         : nextLine = nextLine.replace("$$SITE_URL$$", fqUri);

            fout.write(nextLine);

os.chdir("..");

print("Complete!");