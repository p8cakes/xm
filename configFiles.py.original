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

with open("functions.php.out", "wt") as fout:
    with open("functions.php", "rt") as fin:
        for line in fin:

            nextLine = line.replace('\r\n', '\n');

            if ("$$DATABASE_SERVER$$" in line)                  : nextLine = nextLine.replace("$$DATABASE_SERVER$$", dbServerName);
            if ("$$DATABASE_NAME$$" in line)                    : nextLine = nextLine.replace("$$DATABASE_NAME$$", dbName);
            if ("$$DB_USERNAME$$" in line)                      : nextLine = nextLine.replace("$$DB_USERNAME$$", dbUsername);
            if ("$$DB_PASSWORD$$" in line)                      : nextLine = nextLine.replace("$$DB_PASSWORD$$", dbPassword);
            if ("$$SITE_URL$$" in line)                         : nextLine = nextLine.replace("$$SITE_URL$$", fqUri);
            if ("$$COOKIE_QUALIFIER$$" in line)                 : nextLine = nextLine.replace("$$COOKIE_QUALIFIER$$", cookieQualifier);

            fout.write(nextLine);

os.remove("functions.php");
os.rename("functions.php.out", "functions.php");

with open("xm.updated.sql", "wt") as fout:
    with open("xm.sql", "rt") as fin:
        for line in fin:

            nextLine = line.replace('\r\n', '\n');

            if ("$$FIRST_NAME$$" in line)                       : nextLine = nextLine.replace("$$FIRST_NAME$$", firstName);
            if ("$$LAST_NAME$$" in line)                        : nextLine = nextLine.replace("$$LAST_NAME$$", lastName);
            if ("$$YOUR_EMAIL_ADDRESS$$" in line)               : nextLine = nextLine.replace("$$YOUR_EMAIL_ADDRESS$$", yourEmailAddress);

            ''' Comment out the next line only if you know what you're doing! '''
            if ("-- drop database" in line)                     : nextLine = nextLine.replace("-- drop database", "drop database");

            fout.write(nextLine);

'''
os.chdir("_static");

with open("scripts.js.out", "wt") as fout:
    with open("scripts.js", "rt") as fin:
        for line in fin:

            nextLine = line.replace('\r\n', '\n');

            if ("$$SITE_URL$$" in line)                         : nextLine = nextLine.replace("$$SITE_URL$$", fqUri);

            fout.write(line);

os.remove("scripts.js");
os.rename("scripts.js.out", "scripts.js");

os.chdir("..");
'''
print("Complete!");