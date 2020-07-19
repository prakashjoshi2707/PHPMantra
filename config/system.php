<?php
namespace Config;
class Path {
	const SYSTEM_URL = "http://localhost/rkmvcc";
}
class MySQL {
	const DB_TYPE = 'mysql';
	const DB_HOST = 'localhost';
	const DB_NAME = 'phpmantradb';
	const DB_USER = 'root';
	const DB_PASSWORD = 'Polo@1234';
}
class Key {
	// Key for sending sms
	const MSG91_KEY = "141336AbNimuUH58a31218";
	const SENDER_ID = "RKMVCC";
	
	// Key for sending notificaion using firebase
	const FIREBASE_API_KEY = "AAAA_LnbkLs:APA91bEfqckQMjZKM7Uk4hbikCe7yb2lIT2rM2N4258tVlBz_88s9xlJ_miairF0IERM9juF-cE00_4_OunUDVc8nqewZFxY0nolfk4Ze5dR8zIdVizi9RH1qM7d0SvVQ28B3mucJqs8";
	const FIREBASE_URL = 'https://fcm.googleapis.com/fcm/send';
}
class Validate {
	const IS_VALID_ROLLNO = "isValidRollNo";
	const IS_EMPTY = "isEmpty";
	const IS_NOT_EMPTY = "isNotEmpty";
	const IS_VALID_PASSWORD = "isValidPasswd";
	const IS_VALID_PHONE = "isValidPhone";
	const IS_VALID_NAME = "isValidName";
	const IS_VALID_EMAIL = "isValidEmail";
	const IS_VALID_OTP = "isValidOTP";
	const IS_VALID_SELECT_OPTION = "isValidSelectOption";
	const IS_VALID_DOB = "isValidDOB";
	const IS_VALID_RECEIPTNO = "isValidReceiptNo";
}
class CSS{
	const ACTIVE="activevm";
}
