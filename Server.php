<?php
/*
<!--
*	 FILE          : Server.php
*    PROJECT       : Assignment #7 server php
*	 PROGRAMMER    : Divyangbhai Dankhara
*    STUDENET NO.  : 8061566
*	 FIRST VERSION : 2018-12-08
*	 Description   : this is the server file which is writen in the php
                     this server will server the user ajex request like getting all the file names from the MyFiles directory 
                     and load perticular file content and save the file co tent with specific name
                     the data communication of the server and client is make thrugh JSON which contains the key and values.
                     and server call has been made by the ajex insted of submiting entire form to the server.
-->
*/


    /// here we save all the request from the user and save that into the local localvariables so we can access it in our server
    $getFileNames = $_REQUEST["get"];           
    $load = $_REQUEST["load"];  
    $save = $_REQUEST["save"];
    $dir = "./MyFiles";


    /// here when that client had made request to get file names or not if it made this reuqest that we simpty give them all the files name from the MyFiles folder
    if($getFileNames == "filename")     
    {                                    
        $files = scandir($dir);             /// here we scan all the filename from the myfiles dictionary and save it into the array 
        $fileNames = array();               /// here we make the filenames array which can contain all the file that we have read from the 'MyFiles' Directory
        foreach ($files as &$name)          /// this is the for each loop we which go through all the file names which we read with help of the 'scandir' function
        {
            /// we are going through all the names because 'scndir function all return '.' and '..' as the directory name but we dont want that so we just ignor that and save rest of the file names into the newly created array
            if(!(($name == '.') || ($name == "..")))
            {
                array_push($fileNames,$name);
            }
        }
        $myJSON = json_encode($fileNames);          /// here we convert our array into the jsom which we will send back to the client webpage
        echo $myJSON;
    }


    /// here we check that load operantio is empty or not if it not empty then we simply just start eh load openration of the file where we send back the file content
    if(!($load == ""))
    {
        $myJSON =  json_decode($load);                          /// convert json into the json array so we can access the jason content
        $OpenFileName = $myJSON->{'FName'};                     /// extract tthe name of the file which user wants to open
        $filePath = $dir."/".$OpenFileName;                     /// here make the file path to locate the file which we have to read
        $fileContant = file_get_contents($filePath);            /// here we read all the file content of the file and save it into the string
        $myObj->fileName = $OpenFileName;               /// here we save make a array and save the file name into the jason
        $myObj->data = $fileContant;                /// here we save the file content which we have just read from the file and save into array 
        $myJSON = json_encode($myObj);              /// here we convert the array into the json for send it back to the client so it can sho the data into the text area
        echo $myJSON;
    }

    /// here we check that if save call us not empty then we process the saving opertation of the file.
    if(!($save == ""))
    {
        $myJSON =  json_decode($save);                                      /// here we convert the json string into the array to acces it data
        $saveFileName = $myJSON->{'fileName'};                         // here we extract the file name from the json
        

        /// here we check that file actually has the file extention or not
        if (strpos($saveFileName, '.') === false)          
        {
            /// if file does not have any extention then we just simply save this file as text file
            $saveFileName = $saveFileName.".txt";
        }


        $saveFileData = $myJSON->{'data'};          /// here extract the data that we have to save in the file for jason
        $saveFilePath = $dir."/".$saveFileName;     /// here we make the path with file name and whare to create the file and save it into the variable
        $myfile = fopen($saveFilePath, "w")or die("Unable to open file!");              /// here we create the file with name which is given in the saving time
        fwrite($myfile, $saveFileData);                     /// here we write actual file content into the file that we have createed or overriten
        fclose($myfile);                    /// here we saftly close the file so other program can use this file
        echo "Success";
    }  

?>