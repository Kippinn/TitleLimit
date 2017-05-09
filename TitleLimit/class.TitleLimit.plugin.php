<?php if (!defined('APPLICATION')) exit();

// Let's tell to Vanilla "YEAH WE ARE BOLD AND WE LOVE UNICORNS". 
$PluginInfo['titleLimit'] = array(
    'Description' => "This plugin limits the number of words in discussions title. Code based on R_J's work.",
    'Version' => '1.4',
    'Author' => "Kube17",
    'SettingsUrl' => '/settings/titlelimit',
    'AuthorEmail' => 'bobbamac@hotmail.fr',
    'AuthorUrl' => "http://kube17.tk",
    'License' => 'GNU GPLv2'
);          

class TitleLimit extends Gdn_Plugin {
    
    public function Setup() {
        // Add default configuration. If it's already set, he use the config.php value.
        SaveToConfig('TitleLimit.MaxTitleWords', c('TitleLimit.MaxTitleWords', 5));
        
    }
    
    public function SettingsController_TitleLimit_Create($Sender) {
        
        //Asks Garden for making a settings page. Good boy.
        $Sender->Permission('Garden.Settings.Manage'); 

        $Conf = new ConfigurationModule($Sender);   
        $Conf->Initialize(array(   
            'TitleLimit.MaxTitleWords' => array(   
                'Control' => 'textbox',   
                'LabelCode' => t('Words limit in a discussion title'),   
                'Default' => '5' 
            )   
        ));
        
        $Sender->AddSideMenu();
        $Sender->SetData('Title', t('Title Limit Settings')); // I've try to make a french locale with this. Not working WHY ? :'(
        
        $Conf->RenderAll();   
    }
    
    public function discussionModel_beforeSaveDiscussion_handler($sender, $args) {
        if (str_word_count($args['FormPostValues']['Name']) > c('TitleLimit.MaxTitleWords', 5)) { //If the default configuration failed, the plugin use 5 words max.
            $sender->Validation->addValidationResult('Title', sprintf(t('TitleLimit UserAlert', 'Your title is too long. Title must contain %1$s words or less.'), c('TitleLimit.MaxTitleWords'))); //This line alert users when the title contain more than X words. Can be translated
        }

    }

}
//Dont forget to end witha blank line. The next line looks like my life. Empty. :'(
