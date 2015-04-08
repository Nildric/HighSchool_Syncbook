<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Syncbook/cfg/configurationInclude.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Syncbook/cfg/configurationClass.php");

require(SOURCE_PATH . "SabreDAV/CardDAV/cardDAVManagement.php");
require(SOURCE_PATH . "SabreDAV/CardDAV/contactMapper.php");
require(SOURCE_PATH . "SabreDAV/CardDAV/vCardManagement.php");

class ContactController extends Controller {

    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    public function index()
    {
        if (LoginModel::isUserLoggedIn()) {
            $this->View->render('contact/index');
        } else {
            Redirect::home();
        }
    }

    public function addContact() {
        if (LoginModel::isUserLoggedIn()) {
            $this->View->render('contact/addcontact');
        } else {
            Redirect::home();
        }
    }

    public function insertNewContact() {
        // Getting PDO Connection for User
        $connectionPDO = databaseSabreDAVConnectPDO(Session::get('user_name'), new configurationClass());

        // Retrieving AddressBook "Contacts" for User
        $addressBook = cardDAVAddressBookRetrieve($connectionPDO, Session::get('user_name'), "Contacts");
        // Checking if all went well
        if ($addressBook !== FALSE) {
            // Mapping vCardData from insertContact Form
            $vCardData = mapperObjectCard(json_decode(ContactModel::buildNewContact()));
            // Inserting vCard into Database
            vCardCreate($addressBook, $vCardData);
        }
    }
} 