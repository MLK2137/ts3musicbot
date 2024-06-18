<?php


system('clear');

echo '
#########################################
#   TS3AudioBot Instance Installer      #
#########################################
# 1. Create TS3AudioBot instance        #
# 2. Delete TS3AudioBot                 #
#########################################';

echo PHP_EOL;
    $line = readline('Please select a Number: ');

        if ( $line === "1" ): 

            system('clear');

            $name = readline('Set a Name for the TS3Audiobot Instance: ');
            echo PHP_EOL;
            sleep(1);
            $address = readline('Enter the TeamSpeak Connect Address: ');
            echo PHP_EOL;
            sleep(1);
            $port = readline('By deafult, the TeamSpeak Port is 9987 - Please enter the UDP TS3 Port: ');
            echo PHP_EOL;
            sleep(1);
            $password = readline('TeamSpeak Server Password - This is Optional you can blank when the server has no password: ');
            echo PHP_EOL;
            sleep(1);
            $uid = readline('Enter your TeamSpeak UID - For Admin Permissions: ');
            echo PHP_EOL;
            sleep(1);
            $autostart = readline('Ok, this is the last Question, do you like a Autostart for this Instance?: yes or no ');
            echo PHP_EOL;
            sleep(1);


            if ( $autostart === 'yes' ): 

                system('clear');

                echo 'Running the Installation of your TS3AudioBot, please wait a Minute...';
                echo PHP_EOL;
                shell_exec('apt-get install sudo -y; sudo apt-get install screen -y; sudo apt-get install libopus-dev -y; sudo apt-get install ffmpeg -y;');

                system('clear');

                echo 'Downloading TS3AudioBot Archive from Official AnuaCP.de - cdn.anuacp.de ...';
                echo PHP_EOL;
                sleep(2);
                mkdir('/home/TS3AudioBot');
                mkdir('/home/TS3AudioBot/Instance-'.$name.'');
                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/TS3AudioBot.tar.gz; tar -xvf /home/TS3AudioBot/Instance-'.$name.'/TS3AudioBot.tar.gz; rm /home/TS3AudioBot/Instance-'.$name.'/TS3AudioBot.tar.gz;');

                echo 'Wirting the Config files...';
                echo PHP_EOL;
                sleep(1);

                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/ts3audiobot.toml;');

                chdir("/home/TS3AudioBot/Instance-".$name);

                $line=file('ts3audiobot.toml',FILE_SKIP_EMPTY_LINES);
                $line[53]='name = "Instance-'.$name.'"'."\n";
                $content=implode('',$line);
                file_put_contents('ts3audiobot.toml',$content);

                $line=file('ts3audiobot.toml',FILE_SKIP_EMPTY_LINES);
                $line[46]='address = "'.$address.':'.$port.'"'."\n";
                $content=implode('',$line);
                file_put_contents('ts3audiobot.toml',$content);

                $line=file('ts3audiobot.toml',FILE_SKIP_EMPTY_LINES);
                $line[167]='port = '.rand(7000, 9000).''."\n";
                $content=implode('',$line);
                file_put_contents('ts3audiobot.toml',$content);


                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/rights.toml;');
                $line=file('rights.toml',FILE_SKIP_EMPTY_LINES);
                $line[37]='	useruid = [ "'.$uid.'" ]'."\n";
                $content=implode('',$line);
                file_put_contents('rights.toml',$content);


                mkdir("/home/TS3AudioBot/Instance-".$name."/bots");
                mkdir("/home/TS3AudioBot/Instance-".$name."/bots/default");

                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'/bots/default; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/bot.toml;');

                    $line=file('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',FILE_SKIP_EMPTY_LINES);
                    $line[16]='address = "'.$address.':'.$port.'"'."\n";
                    $content=implode('',$line);
                    file_put_contents('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',$content);

                    $line=file('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',FILE_SKIP_EMPTY_LINES);
                    $line[10]='server_password = { pw = "'.$password.'" }'."\n";
                    $content=implode('',$line);
                    file_put_contents('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',$content);

                system('clear');

                echo 'Creating a Autostart for the TS3AudioBot via systemd service...';
                echo PHP_EOL;

// Open a file named geeks_data in write mode
$data = fopen("/home/TS3AudioBot/Instance-".$name."/start.sh", "w");
  
// writing content to a file using fwrite() function
echo fwrite($data, "#!/bin/sh

cd /home/TS3AudioBot/Instance-".$name."

su -c '/usr/bin/screen -S TS3AudioBot-".$name." -d -m ./TS3AudioBot' root

echo 'Run OK!'");
  
// closing the file
fclose($data);

// Open a file named geeks_data in write mode
$data = fopen("/home/TS3AudioBot/Instance-".$name."/stop.sh", "w");
  
// writing content to a file using fwrite() function
echo fwrite($data, "#!/bin/sh

su -c '/usr/bin/screen -S TS3AudioBot-".$name." -X quit;' root

echo 'Stop OK!'");
  
// closing the file
fclose($data);


shell_exec("apt-get -y install dos2unix; chmod 777 /home/TS3AudioBot/Instance-".$name."/stop.sh; dos2unix /home/TS3AudioBot/Instance-".$name."/stop.sh");
shell_exec("apt-get -y install dos2unix; chmod 777 /home/TS3AudioBot/Instance-".$name."/start.sh; dos2unix /home/TS3AudioBot/Instance-".$name."/start.sh");



                shell_exec("
echo '
[Unit]
Description=TS3AudioBot Installer v1 - Linux
After=network.target
StartLimitIntervalSec=0

[Service]
Type=oneshot
RemainAfterExit=yes
Restart=on-failure
RestartSec=3s
User=root
ExecStart=/bin/sh /home/TS3AudioBot/Instance-".$name."/start.sh
ExecStop=/bin/sh /home/TS3AudioBot/Instance-".$name."/stop.sh

[Install]
WantedBy=multi-user.target
' > /etc/systemd/system/TS3AB-".$name.".service");

                    shell_exec("sudo systemctl enable TS3AB-".$name.".service");
                    shell_exec("sudo systemctl daemon-reload");
                    shell_exec("sudo service TS3AB-".$name." start");

                    sleep(2);
                    echo PHP_EOL;
                    readline('Installation process finished successfully! [ENTER for close this Message]');

            else:

                system('clear');

                echo 'Running the Installation of your TS3AudioBot, please wait a Minute...';
                echo PHP_EOL;
                shell_exec('apt-get install sudo -y; sudo apt-get install screen -y; sudo apt-get install libopus-dev -y; sudo apt-get install ffmpeg -y;');

                system('clear');

                echo 'Downloading TS3AudioBot Archive from Official AnuaCP.de - cdn.anuacp.de ...';
                echo PHP_EOL;
                sleep(2);
                mkdir('/home/TS3AudioBot');
                mkdir('/home/TS3AudioBot/Instance-'.$name.'');
                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/TS3AudioBot.tar.gz; tar -xvf /home/TS3AudioBot/Instance-'.$name.'/TS3AudioBot.tar.gz; rm /home/TS3AudioBot/Instance-'.$name.'/TS3AudioBot.tar.gz;');

                echo 'Wirting the Config files...';
                echo PHP_EOL;
                sleep(1);

                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/ts3audiobot.toml;');
                
                chdir("/home/TS3AudioBot/Instance-".$name);
                
                $line=file('ts3audiobot.toml',FILE_SKIP_EMPTY_LINES);
                $line[53]='name = "Instance-'.$name.'"'."\n";
                $content=implode('',$line);
                file_put_contents('ts3audiobot.toml',$content);

                $line=file('ts3audiobot.toml',FILE_SKIP_EMPTY_LINES);
                $line[46]='address = "'.$address.':'.$port.'"'."\n";
                $content=implode('',$line);
                file_put_contents('ts3audiobot.toml',$content);

                $line=file('ts3audiobot.toml',FILE_SKIP_EMPTY_LINES);
                $line[167]='port = '.rand(7000, 9000).''."\n";
                $content=implode('',$line);
                file_put_contents('ts3audiobot.toml',$content);


                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/rights.toml;');
                $line=file('rights.toml',FILE_SKIP_EMPTY_LINES);
                $line[37]='	useruid = [ "'.$uid.'" ]'."\n";
                $content=implode('',$line);
                file_put_contents('rights.toml',$content);


                mkdir("/home/TS3AudioBot/Instance-".$name."/bots");
                mkdir("/home/TS3AudioBot/Instance-".$name."/bots/default");

                shell_exec('cd /home/TS3AudioBot/Instance-'.$name.'/bots/default; wget --no-check-certificate https://cdn.anuacp.de/ts3audiobot/bot.toml;');

                    $line=file('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',FILE_SKIP_EMPTY_LINES);
                    $line[16]='address = "'.$address.':'.$port.'"'."\n";
                    $content=implode('',$line);
                    file_put_contents('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',$content);

                    $line=file('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',FILE_SKIP_EMPTY_LINES);
                    $line[10]='server_password = { pw = "'.$password.'" }'."\n";
                    $content=implode('',$line);
                    file_put_contents('/home/TS3AudioBot/Instance-'.$name.'/bots/default/bot.toml',$content);

                system('clear');


                system('clear');

// Open a file named geeks_data in write mode
$data = fopen("/home/TS3AudioBot/Instance-".$name."/start.sh", "w");
  
// writing content to a file using fwrite() function
echo fwrite($data, "#!/bin/sh

cd /home/TS3AudioBot/Instance-".$name."

su -c '/usr/bin/screen -S TS3AudioBot-".$name." -d -m ./TS3AudioBot' root

echo 'Run OK!'");
  
// closing the file
fclose($data);

// Open a file named geeks_data in write mode
$data = fopen("/home/TS3AudioBot/Instance-".$name."/stop.sh", "w");
  
// writing content to a file using fwrite() function
echo fwrite($data, "#!/bin/sh

su -c '/usr/bin/screen -S TS3AudioBot-".$name." -X quit;' root

echo 'Stop OK!'");
  
// closing the file
fclose($data);

shell_exec("apt-get -y install dos2unix; chmod 777 /home/TS3AudioBot/Instance-".$name."/stop.sh; dos2unix /home/TS3AudioBot/Instance-".$name."/stop.sh");
shell_exec("apt-get -y install dos2unix; chmod 777 /home/TS3AudioBot/Instance-".$name."/start.sh; dos2unix /home/TS3AudioBot/Instance-".$name."/start.sh");

                echo 'Installation process successfully - Now we can start the application...';
                echo PHP_EOL;
                sleep(1);
                shell_exec("/home/TS3AudioBot/Instance-".$name."/start.sh");
                echo PHP_EOL;
                readline('The TS3AudioBot is running... - After reboot you must restart the TS3AudioBot Manually [ENTER]: ');


            endif;

        endif;

        if ( $line === "2" ):


            system('clear');

            $name = readline('Please enter your TS3AudioBot Instance Name: ');
            echo PHP_EOL;
            sleep(1);

            readline('Instance: '.$name.' - Will be deleted! Press Enter to continue or STRG+C to cancel: ');

            shell_exec("/home/TS3AudioBot/Instance-".$name."/stop.sh");

            shell_exec('sudo systemctl disable TS3AB-'.$name.'');
            shell_exec('sudo systemctl daemon-reload');

            shell_exec('rm -r /home/TS3AudioBot/Instance-'.$name.'');

            sleep(2);

            readline('TS3AudioBot Instance successfully deleted! - [PRESS ENTER]: ');

        endif;