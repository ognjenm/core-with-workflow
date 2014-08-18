<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedLast extends Migration {

	public function up()
	{ 
		\Telenok\Object\Sequence::all()->each(function($item)
		{
			$item->model()->get()->each(function($i)
			{
				$i->storeOrUpdate([
					'start_at' => null, 
					'end_at' => null,
					'created_at' => \DB::raw('NOW()'),
					'updated_at' => \DB::raw('NOW()')
				]);
			});
		});
		
		/*
		\Telenok\Object\Sequence::all()->each(function($item)
		{
			\DB::table($item->model()->get)->update([

				]);
		});
		*/
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'German', 'locale' => 'de', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'French', 'locale' => 'fr', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Dutch', 'locale' => 'nl', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Italian', 'locale' => 'it', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Spanish', 'locale' => 'es', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Polish', 'locale' => 'pl', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Russian', 'locale' => 'ru', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Japanese', 'locale' => 'ja', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Portuguese', 'locale' => 'pt', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Swedish', 'locale' => 'sv', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Chinese', 'locale' => 'zh', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Catalan', 'locale' => 'ca', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Ukrainian', 'locale' => 'uk', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Norwegian (Bokmål)', 'locale' => 'no', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Finnish', 'locale' => 'fi', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Vietnamese', 'locale' => 'vi', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Czech', 'locale' => 'cs', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Hungarian', 'locale' => 'hu', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Korean', 'locale' => 'ko', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Indonesian', 'locale' => 'id', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Turkish', 'locale' => 'tr', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Romanian', 'locale' => 'ro', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Persian', 'locale' => 'fa', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Arabic', 'locale' => 'ar', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Danish', 'locale' => 'da', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Esperanto', 'locale' => 'eo', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Serbian', 'locale' => 'sr', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Lithuanian', 'locale' => 'lt', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Slovak', 'locale' => 'sk', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Malay', 'locale' => 'ms', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Hebrew', 'locale' => 'he', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Bulgarian', 'locale' => 'bg', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Slovenian', 'locale' => 'sl', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Volapük', 'locale' => 'vo', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Kazakh', 'locale' => 'kk', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Waray-Waray', 'locale' => 'war', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Basque', 'locale' => 'eu', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Croatian', 'locale' => 'hr', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Hindi', 'locale' => 'hi', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Estonian', 'locale' => 'et', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Azerbaijani', 'locale' => 'az', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Galician', 'locale' => 'gl', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Simple English', 'locale' => 'simple', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Norwegian (Nynorsk)', 'locale' => 'nn', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Thai', 'locale' => 'th', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Newar / Nepal Bhasa', 'locale' => 'new', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Greek', 'locale' => 'el', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Aromanian', 'locale' => 'roa-rup', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Latin', 'locale' => 'la', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Occitan', 'locale' => 'oc', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Tagalog', 'locale' => 'tl', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Haitian', 'locale' => 'ht', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Macedonian', 'locale' => 'mk', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Georgian', 'locale' => 'ka', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Serbo-Croatian', 'locale' => 'sh', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Telugu', 'locale' => 'te', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Piedmontese', 'locale' => 'pms', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Cebuano', 'locale' => 'ceb', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Tamil', 'locale' => 'ta', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Belarusian (Taraškievica)', 'locale' => 'be-x-old', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Breton', 'locale' => 'br', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Latvian', 'locale' => 'lv', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Javanese', 'locale' => 'jv', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Albanian', 'locale' => 'sq', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Belarusian', 'locale' => 'be', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Marathi', 'locale' => 'mr', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Welsh', 'locale' => 'cy', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Luxembourgish', 'locale' => 'lb', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Icelandic', 'locale' => 'is', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Bosnian', 'locale' => 'bs', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Yoruba', 'locale' => 'yo', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Malagasy', 'locale' => 'mg', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Aragonese', 'locale' => 'an', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Bishnupriya Manipuri', 'locale' => 'bpy', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Lombard', 'locale' => 'lmo', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'West Frisian', 'locale' => 'fy', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Bengali', 'locale' => 'bn', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Ido', 'locale' => 'io', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Swahili', 'locale' => 'sw', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Gujarati', 'locale' => 'gu', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Malayalam', 'locale' => 'ml', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Western Panjabi', 'locale' => 'pnb', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Afrikaans', 'locale' => 'af', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Low Saxon', 'locale' => 'nds', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Sicilian', 'locale' => 'scn', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Urdu', 'locale' => 'ur', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Kurdish', 'locale' => 'ku', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Cantonese', 'locale' => 'zh-yue', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Armenian', 'locale' => 'hy', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Quechua', 'locale' => 'qu', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Sundanese', 'locale' => 'su', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Nepali', 'locale' => 'ne', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Zazaki', 'locale' => 'diq', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Asturian', 'locale' => 'ast', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Tatar', 'locale' => 'tt', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Neapolitan', 'locale' => 'nap', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Irish', 'locale' => 'ga', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Chuvash', 'locale' => 'cv', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Samogitian', 'locale' => 'bat-smg', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Walloon', 'locale' => 'wa', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Amharic', 'locale' => 'am', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Kannada', 'locale' => 'kn', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Alemannic', 'locale' => 'als', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Buginese', 'locale' => 'bug', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Burmese', 'locale' => 'my', 'active' => 1]);
		(new \Telenok\System\Language())->storeOrUpdate(['title' => 'Interlingua', 'locale' => 'ia', 'active' => 1]);

		
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => '3D Studio MAX graphics file', 'extension' => '3ds', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Multimedia files for wireless networks', 'extension' => '3gp', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => '7-ZIP compressed 7z archive file', 'extension' => '7z', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'WinACE Compressed ace archive file', 'extension' => 'ace', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Adobe Illustrator graphics file', 'extension' => 'ai', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Adobe AIR rich Internet applications', 'extension' => 'air', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Google Android package file', 'extension' => 'apk', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Active Server Page script page', 'extension' => 'asp', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Active Server Page Extended ASP.NET script file', 'extension' => 'aspx', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Audio Video Interleave movie file', 'extension' => 'avi', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'BitTorrent unfinished download file', 'extension' => 'bc', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Standard Windows Bitmap image', 'extension' => 'bmp', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Windows Cabinet Compressed Archive', 'extension' => 'cab', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'CorelDRAW vector or bitmap file', 'extension' => 'cdr', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'C++ main source code file format', 'extension' => 'cpp', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'DjVu image file', 'extension' => 'djvu', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Microsoft Word 97 to 2003 document file', 'extension' => 'doc', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Microsoft Word 2007/2010 Open XML document file', 'extension' => 'docx', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'AutoCAD Drawing file', 'extension' => 'dwg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Email message file', 'extension' => 'eml', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Program executable file', 'extension' => 'exe', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Flash video file', 'extension' => 'flv', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Graphics interchange file format', 'extension' => 'gif', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'GNU ZIP gzip compressed archive file', 'extension' => 'gzip', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'HTML Hypertext Markup Language web page file', 'extension' => 'htm', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'HTML Hypertext Markup Language web page file', 'extension' => 'html', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'CD/DVD/HD DVD/Blu-ray disc ISO binary image file', 'extension' => 'ico', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Text configuration file', 'extension' => 'ini', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Icon file', 'extension' => 'iso', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Compressed archive file package for Java classes and data file', 'extension' => 'jar', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Java language source code file', 'extension' => 'java', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'JPEG bitmap image format file', 'extension' => 'jpg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'JPEG bitmap image format file', 'extension' => 'jpeg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'JavaScript object notation file', 'extension' => 'json', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'MIDI-sequention sound file', 'extension' => 'midi', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Matroska video-audio multimedia file', 'extension' => 'mkv', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Apple QuickTime digital movie file', 'extension' => 'mov', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Compressed audio and music file, mp3 songs or ringtones', 'extension' => 'mp3', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'MPEG-4 video file format', 'extension' => 'mp4', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'MPEG 1 video file format', 'extension' => 'mpg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'MPEG 1 video file format', 'extension' => 'mpeg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Open Document Interchange Format', 'extension' => 'odf', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'ODF text document file', 'extension' => 'odt', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Ogg Vorbis audio file', 'extension' => 'ogg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Adobe Portable document format', 'extension' => 'pdf', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'PHP script or page', 'extension' => 'php', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Portable Network Graphic file', 'extension' => 'png', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'WinRAR RAR compressed archive', 'extension' => 'rar', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Digital camera photo RAW image format', 'extension' => 'raw', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Linux package manager file', 'extension' => 'rpm', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Really Simple Syndication', 'extension' => 'rss', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Rich Text Format document', 'extension' => 'rtf', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Structured Query Language Data SQL file', 'extension' => 'sql', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'XML based vector graphics format', 'extension' => 'svg', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'ShockWave Flash', 'extension' => 'swf', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Unix standard Archive format, Tape Archive', 'extension' => 'tar', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Old version Gzip compressed TAR Archive', 'extension' => 'gz', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Gzip compressed TAR archive', 'extension' => 'tgz', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Aldus Tagged Image File Format', 'extension' => 'tif', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Aldus Tagged Image File Format', 'extension' => 'tiff', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Simple text file', 'extension' => 'txt', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Windows Media Audio', 'extension' => 'wma', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Windows Media Video file', 'extension' => 'wmv', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Extensible HTML file', 'extension' => 'xhtml', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Microsoft Excel 97 to 2003 workbook file', 'extension' => 'xls', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'Microsoft Excel 2007, Excel 2010 and Excel 2013 Open XML workbook file', 'extension' => 'xlsx', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'XML document file', 'extension' => 'xml', 'active' => 1]);
		(new \Telenok\File\FileExtension())->storeOrUpdate(['title' => 'ZIP compressed archive', 'extension' => 'zip', 'active' => 1]);

		
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Atom', 'mime_type' => 'application/atom+xml', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'JavaScript Object Notation JSON', 'mime_type' => 'application/json', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'JavaScript', 'mime_type' => 'application/javascript', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Arbitrary binary data', 'mime_type' => 'application/octet-stream', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'ECMAScript/JavaScript', 'mime_type' => 'application/ecmascript', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Ogg', 'mime_type' => 'application/ogg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Portable Document Format', 'mime_type' => 'application/pdf', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'XHTML', 'mime_type' => 'application/xhtml+xml', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'XML', 'mime_type' => 'application/xml', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'ZIP', 'mime_type' => 'application/zip', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Gzip', 'mime_type' => 'application/gzip', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Microsoft Excel files', 'mime_type' => 'application/vnd.ms-excel', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'MP4 audio', 'mime_type' => 'audio/mp4', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'MP3 or other MPEG audio', 'mime_type' => 'audio/mpeg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Ogg VorbisOgg Vorbi, Speex, Flac and other audio', 'mime_type' => 'audio/ogg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'WebM open media format', 'mime_type' => 'audio/webm', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'GIF image', 'mime_type' => 'image/gif', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'JPEG JFIF image', 'mime_type' => 'image/jpeg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'JPEG JFIF image', 'mime_type' => 'image/pjpeg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'PNG Portable Network Graphics', 'mime_type' => 'image/png', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'SVG vector image', 'mime_type' => 'image/svg+xml', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Cascading Style Sheets', 'mime_type' => 'text/css', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'CSV Comma-separated values', 'mime_type' => 'text/csv', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'HTML', 'mime_type' => 'text/html', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'JavaScript', 'mime_type' => 'text/javascript', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Textual data', 'mime_type' => 'text/plain', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'XML Extensible Markup Language', 'mime_type' => 'text/xml', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'MPEG-1 video with multiplexed audio', 'mime_type' => 'video/mpeg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'MP4 video', 'mime_type' => 'video/mp4', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'Ogg Theora or other video', 'mime_type' => 'video/ogg', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'QuickTime video', 'mime_type' => 'video/quicktime', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'WebM Matroska-based open media format', 'mime_type' => 'video/webm', 'active' => 1]);
		(new \Telenok\File\FileMimeType())->storeOrUpdate(['title' => 'FLV Flash video', 'mime_type' => 'video/x-flv', 'active' => 1]);
 
		
		(new \Telenok\Object\Field())->storeOrUpdate([
			'title' => SeedGroupTableTranslation::get('group.field.role'),
			'title_list' => SeedGroupTableTranslation::get('group.field.role'),
			'key' => 'relation-many-to-many',
			'code' => 'role',
			'active' => 1,
			'field_object_type' => 'group',
			'relation_many_to_many_has' => 'role',
			'field_object_tab' => 'main',
			'multilanguage' => 0,
			'show_in_form' => 1,
			'show_in_list' => 1,
			'allow_search' => 1,
			'allow_create' => 1,
			'allow_update' => 1,
			'field_order' => 6,
		]);

		//Resource
		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Control Panel', 'ru' => 'Панель управления'],
			'code' => 'control_panel',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'User authorized', 'ru' => 'Авторизованный пользователь'],
			'code' => 'user_authorized',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'User unauthorized', 'ru' => 'Неавторизованный пользователь'],
			'code' => 'user_unauthorized',
			'active' => 1
		]);
/*
		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Type of object (existing): Object Type', 'ru' => 'Тип объекта (существующий): Тип объекта'],
			'code' => 'object_type.object_type',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Type of object (existing): Object Field', 'ru' => 'Тип объекта (существующий): Поле объекта'],
			'code' => 'object_type.object_field',
			'active' => 1
		]);
*/
		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: Field', 'ru' => 'Модуль: Поле'],
			'code' => 'module.objects-field',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: Type', 'ru' => 'Модуль: Тип'],
			'code' => 'module.objects-type',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: List', 'ru' => 'Модуль: Список'],
			'code' => 'module.objects-lists',
			'active' => 1
		]);
		
		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: File browser', 'ru' => 'Модуль: Обзор Файлов'],
			'code' => 'module.file-browser',
			'active' => 1
		]);		

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: Setting', 'ru' => 'Модуль: Настройки'],
			'code' => 'module.system-setting',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: Object Version', 'ru' => 'Модуль: Версии объектов'],
			'code' => 'module.objects-version',
			'active' => 1
		]);

		(new \Telenok\Security\Resource())->storeOrUpdate([
			'title' => ['en' => 'Module: Web Page', 'ru' => 'Модуль: Веб Страница'],
			'code' => 'module.web-page',
			'active' => 1
		]);


		//User fields
		(new \Telenok\Object\Field())->storeOrUpdate(
			[
				'title' => ['en' => 'Firstname', 'ru' => "Имя"],
				'title_list' => ['en' => 'Firstname', 'ru' => "Имя"],
				'key' => 'string',
				'code' => 'firstname',
				'active' => 1,
				'field_object_type' => 'user',
				'field_object_tab' => 'main',
				'show_in_form' => 1,
				'show_in_list' => 0,
				'allow_search' => 1,
				'multilanguage' => 0,
				'allow_create' => 1,
				'allow_update' => 1, 
				'field_order' => 5,
			]
		);

		(new \Telenok\Object\Field())->storeOrUpdate(
			[
				'title' => ['en' => 'Middlename', 'ru' => "Отчество"],
				'title_list' => ['en' => 'Middlename', 'ru' => "Отчество"],
				'key' => 'string',
				'code' => 'middlename',
				'active' => 1,
				'field_object_type' => 'user',
				'field_object_tab' => 'main',
				'show_in_form' => 1,
				'show_in_list' => 0,
				'allow_search' => 1,
				'multilanguage' => 0,
				'allow_create' => 1,
				'allow_update' => 1, 
				'field_order' => 6,
			]
		);

		(new \Telenok\Object\Field())->storeOrUpdate(
			[
				'title' => ['en' => 'Lastname', 'ru' => "Фамилия"],
				'title_list' => ['en' => 'Lastname', 'ru' => "Фамилия"],
				'key' => 'string',
				'code' => 'lastname',
				'active' => 1,
				'field_object_type' => 'user',
				'field_object_tab' => 'main',
				'show_in_form' => 1,
				'show_in_list' => 0,
				'allow_search' => 1,
				'multilanguage' => 0,
				'allow_create' => 1,
				'allow_update' => 1, 
				'field_order' => 7,
			]
		);

		(new \Telenok\Object\Field())->storeOrUpdate([
			'title' => ['en' => 'Telephone', 'ru' => 'Телефон'],
			'title_list' => ['en' => 'Telephone', 'ru' => 'Телефон'],
			'key' => 'string',
			'code' => 'telephone',
			'active' => 1,
			'field_object_type' => 'user',
			'show_in_form' => 1,
			'field_order' => 8,
			'field_object_tab' => 'main',
		]); 

		//User superadmin
		$user = (new \Telenok\User\User())->storeOrUpdate([
			'title' => 'Super Administrator',
			'username' => 'admin',
			'usernick' => 'Super administrator',
			'email' => 'support@telenok.com',
			'password' => '11111',
			'active' => 1,
		]);

		//Login User
		Auth::login($user);

		//Group
		(new \Telenok\User\Group())->storeOrUpdate([
			'title' => ['en' => 'Super administrator', 'ru' => 'Супер администратор'],
			'code' => 'super_administrator',
			'active' => 1,
		]);

		//Role
		(new \Telenok\Security\Role())->storeOrUpdate([
			'title' => ['en' => 'Super administrator', 'ru' => 'Супер администратор'],
			'code' => 'super_administrator',
			'active' => 1,
		]);

		(new \Telenok\Security\Role())->storeOrUpdate([
			'title' => ['en' => 'Control panel', 'ru' => 'Control panel'],
			'code' => 'control_panel',
			'active' => 1,
		]);


		//Workflow status
		/*
		(new \Telenok\Workflow\Status())->storeOrUpdate([
			'title' => ['en' => 'Draft', 'ru' => 'Черновик'],
			'code' => 'draft',
			'active' => 1,
		]);

		(new \Telenok\Workflow\Status())->storeOrUpdate([
			'title' => ['en' => 'Expects agreement', 'ru' => 'Ожидает согласования'],
			'code' => 'accepting',
			'active' => 1,
		]);

		(new \Telenok\Workflow\Status())->storeOrUpdate([
			'title' => ['en' => 'Declined', 'ru' => 'Отклонен'],
			'code' => 'declined',
			'active' => 1,
		]);

		(new \Telenok\Workflow\Status())->storeOrUpdate([
			'title' => ['en' => 'Accepted', 'ru' => 'Одобрен'],
			'code' => 'accepted',
			'active' => 1,
		]);

		(new \Telenok\Workflow\Status())->storeOrUpdate([
			'title' => ['en' => 'Active', 'ru' => 'Активно'],
			'code' => 'active',
			'active' => 1,
		]);
		*/


		//Permission
		(new \Telenok\Security\Permission())->storeOrUpdate([
			'title' => ['en' => 'Create', 'ru' => 'Создание'],
			'code' => 'create',
			'active' => 1,
		]);

		(new \Telenok\Security\Permission())->storeOrUpdate([
			'title' => ['en' => 'Read', 'ru' => 'Чтение'],
			'code' => 'read',
			'active' => 1,
		]);

		(new \Telenok\Security\Permission())->storeOrUpdate([
			'title' => ['en' => 'Update', 'ru' => 'Изменение'],
			'code' => 'update',
			'active' => 1,
		]);

		(new \Telenok\Security\Permission())->storeOrUpdate([
			'title' => ['en' => 'Delete', 'ru' => 'Удаление'],
			'code' => 'delete',
			'active' => 1,
		]);

		\Telenok\Object\Type::all()->each(function($item) use ($user) 
		{
			\DB::table( $item->code )
			->update(
				[
					'created_by_user' => $user->getKey(),
					'updated_by_user' => $user->getKey(),
					'created_at' => \DB::raw('NOW()'),
					'updated_at' => \DB::raw('NOW()'),
					'start_at' => \DB::raw('NOW()'),
					'end_at' => \DB::raw('NOW() + INTERVAL 10 YEAR'),
				]
			);
		});

		//ACL
		$user = \Telenok\User\User::where('username', 'admin')->first();
		$groupSuperAdmin = \Telenok\User\Group::where('code', 'super_administrator')->first();
		$roleSuperAdmin = \Telenok\Security\Role::where('code', 'super_administrator')->first();

		\Telenok\Core\Security\Acl::user($user)->setGroup($groupSuperAdmin);
		\Telenok\Core\Security\Acl::group($groupSuperAdmin)->setRole($roleSuperAdmin);


		//Folder
		$folderSystem = (new \Telenok\System\Folder())->storeOrUpdate([
					'title' => ['en' => 'System', 'ru' => 'Система'],
					'active' => 1,
					'code' => 'system',
				])->makeRoot();

		$folderUser = (new \Telenok\System\Folder())->storeOrUpdate([
					'title' => ['en' => 'User', 'ru' => 'Пользователь'],
					'active' => 1,
					'code' => 'user',
				])->makeRoot();

		$folderOther = (new \Telenok\System\Folder())->storeOrUpdate([
					'title' => ['en' => 'Other', 'ru' => 'Другое'],
					'active' => 1,
					'code' => 'other',
				])->makeRoot();

		$folderFile = (new \Telenok\System\Folder())->storeOrUpdate([
					'title' => ['en' => 'File', 'ru' => 'Файл'],
					'active' => 1,
					'code' => 'file',
				])->makeRoot();

		$folderBusinessProcess = (new \Telenok\System\Folder())->storeOrUpdate([
					'title' => ['en' => 'Business process', 'ru' => 'Бизнес-процесс'],
					'active' => 1,
					'code' => 'business_process',
				])->makeRoot();

		$folderWeb = (new \Telenok\System\Folder())->storeOrUpdate([
					'title' => ['en' => 'Web', 'ru' => 'Веб'],
					'active' => 1,
					'code' => 'web',
				])->makeRoot();


		\Telenok\Object\Type::where('code', 'object_type')->first()->makeLastChildOf($folderSystem);
		\Telenok\Object\Type::where('code', 'object_field')->first()->makeLastChildOf($folderSystem);
		\Telenok\Object\Type::where('code', 'language')->first()->makeLastChildOf($folderSystem);
		\Telenok\Object\Type::where('code', 'object_tab')->first()->makeLastChildOf($folderSystem);
		\Telenok\Object\Type::where('code', 'setting')->first()->makeLastChildOf($folderSystem);

		
		
		\Telenok\Object\Type::where('code', 'user')->first()->makeLastChildOf($folderUser);
		\Telenok\Object\Type::where('code', 'permission')->first()->makeLastChildOf($folderUser);
		\Telenok\Object\Type::where('code', 'role')->first()->makeLastChildOf($folderUser);
		\Telenok\Object\Type::where('code', 'resource')->first()->makeLastChildOf($folderUser);
		\Telenok\Object\Type::where('code', 'group')->first()->makeLastChildOf($folderUser);
		\Telenok\Object\Type::where('code', 'user_message')->first()->makeLastChildOf($folderUser);
		\Telenok\Object\Type::where('code', 'subject_permission_resource')->first()->makeLastChildOf($folderUser);

		\Telenok\Object\Type::where('code', 'folder')->first()->makeLastChildOf($folderOther);
		\Telenok\Object\Type::where('code', 'module')->first()->makeLastChildOf($folderOther);
		\Telenok\Object\Type::where('code', 'module_group')->first()->makeLastChildOf($folderOther);
		\Telenok\Object\Type::where('code', 'widget')->first()->makeLastChildOf($folderOther);
		\Telenok\Object\Type::where('code', 'widget_group')->first()->makeLastChildOf($folderOther);
		\Telenok\Object\Type::where('code', 'object_version')->first()->makeLastChildOf($folderOther);
		\Telenok\Object\Type::where('code', 'object_sequence')->first()->makeLastChildOf($folderOther);

		\Telenok\Object\Type::where('code', 'file')->first()->makeLastChildOf($folderFile);
		\Telenok\Object\Type::where('code', 'file_category')->first()->makeLastChildOf($folderFile);
		\Telenok\Object\Type::where('code', 'file_mime_type')->first()->makeLastChildOf($folderFile);
		\Telenok\Object\Type::where('code', 'file_extension')->first()->makeLastChildOf($folderFile);

		\Telenok\Object\Type::where('code', 'page')->first()->makeLastChildOf($folderWeb);
		\Telenok\Object\Type::where('code', 'page_controller')->first()->makeLastChildOf($folderWeb);
		\Telenok\Object\Type::where('code', 'widget_on_page')->first()->makeLastChildOf($folderWeb);
/*
		\Telenok\Object\Type::where('code', 'workflow_status')->first()->makeLastChildOf($folderBusinessProcess);
		\Telenok\Object\Type::where('code', 'workflow_process')->first()->makeLastChildOf($folderBusinessProcess);
		\Telenok\Object\Type::where('code', 'workflow_event')->first()->makeLastChildOf($folderBusinessProcess);
		\Telenok\Object\Type::where('code', 'workflow_event_resource')->first()->makeLastChildOf($folderBusinessProcess);
		\Telenok\Object\Type::where('code', 'workflow_thread')->first()->makeLastChildOf($folderBusinessProcess);
*/
		//Module group
		(new \Telenok\Web\ModuleGroup())->storeOrUpdate([
			'title' => ['en' => 'Content', 'ru' => 'Содержание'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\ModuleGroup\Content\Controller',
		]);

		(new \Telenok\Web\ModuleGroup())->storeOrUpdate([
			'title' => ['en' => 'Setting', 'ru' => 'Настройки'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\ModuleGroup\Setting\Controller',
		]);

		//Module
		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Users', 'ru' => 'Пользователи'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Users\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Profile', 'ru' => 'Профиль'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Users\Profile\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Objects', 'ru' => 'Объекты'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Objects\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Type', 'ru' => 'Типы'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Objects\Type\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Field', 'ru' => 'Поле'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Objects\Field\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Lists', 'ru' => 'Список'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Objects\Lists\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Version', 'ru' => 'Версии'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Objects\Version\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Workflow', 'ru' => 'Workflow'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Workflow\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Process', 'ru' => 'Process'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Workflow\Process\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Thread', 'ru' => 'Thread'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Workflow\Thread\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Page', 'ru' => 'Страница'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Web\Page\Controller',
		]);

		(new \Telenok\Web\Module())->storeOrUpdate([
			'title' => ['en' => 'Web', 'ru' => 'Веб'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Module\Web\Controller',
		]);

 

		// Widget group
		(new \Telenok\Web\WidgetGroup())->storeOrUpdate([
			'title' => ['en' => 'Standart', 'ru' => 'Стандартные'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\WidgetGroup\Standart\Controller',
		]);

		// Widget
		(new \Telenok\Web\Widget())->storeOrUpdate([
			'title' => ['en' => 'HTML', 'ru' => 'HTML'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Widget\Html\Controller',
		]);

		(new \Telenok\Web\Widget())->storeOrUpdate([
			'title' => ['en' => 'Table', 'ru' => 'Таблица'],
			'active' => 1,
			'controller_class' => 'Telenok\Core\Widget\Table\Controller',
		]);


		
		
		\Telenok\Object\Type::all()->each(function($item)
		{
			if ($item->treeable && !$item->field()->where('code', 'tree_parent')->count())
			{
				$modelField = new \Telenok\Object\Field();
				
				$modelField->storeOrUpdate([
					'key' => 'tree',
					'field_object_type' => $item->getKey(),
					'field_object_tab' => 'main',
					'field_order' => 10,
				]); 
				
				$modelField = null;
				
				$modelField = new \Telenok\Object\Field();

				try
				{
					$modelField->storeOrUpdate([
						'key' => 'permission',
						'field_object_type' => $item->getKey(),
					]); 
				} catch (\Exception $ex) {}

				$modelField = null;
			}
		});
		 


		/*
		  $controllerList->storeOrUpdate([
		  'title' => ['en' => 'Files', 'ru' => 'Files'],
		  'active' => 1,
		  'controller_class' => 'Telenok\Core\Module\Files\Controller',
		  ], 'module');

		  $controllerList->storeOrUpdate([
		  'title' => ['en' => 'Browser', 'ru' => 'Browser'],
		  'active' => 1,
		  'controller_class' => 'Telenok\Core\Module\FileBrowser\Controller',
		  ], 'module');
		 */

		/*
		  //set Active status to entities
		  $statusActive = \Telenok\Workflow\Status::where('code', 'active')->first();

		  \Telenok\Object\Type::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\Object\Field::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\User\User::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\Workflow\Status::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\Security\Resource::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\System\Language::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\User\Group::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\Security\Role::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\Security\Permission::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		  \Telenok\User\RolePermissionResource::all()->each(function($item) use ($statusActive) { $item->workflowStatus()->save($statusActive); });
		 */

		
		\Telenok\Object\Type::all()->each(function($item) 
		{
			$modelClassNew = str_replace('\Core\Model', '', $item->class_model);
			
			if (class_exists($modelClassNew))
			{
				$item->update(['class_model' => $modelClassNew]);
			}
		});
		
		\Telenok\Object\Sequence::all()->each(function($item) 
		{
			$modelClassNew = str_replace('\Core\Model', '', $item->class_model);
			
			if (class_exists($modelClassNew))
			{
				$item->update(['class_model' => $modelClassNew]);
			}
		});

		//Setting
		(new \Telenok\System\Setting())->storeOrUpdate([
			'title' => ['en' => 'ACL enabled', 'ru' => 'ACL разрешено'],
			'active' => 1,
			'value' => 0,
			'code' => 'app.acl.enabled',
		]);

		(new \Telenok\System\Setting())->storeOrUpdate([
			'title' => ['en' => 'Workflow enabled', 'ru' => 'Workflow разрешено'],
			'active' => 1,
			'value' => 0,
			'code' => 'app.workflow.enabled',
		]);

		(new \Telenok\System\Setting())->storeOrUpdate([
			'title' => ['en' => 'Version enabled', 'ru' => 'Version разрешено'],
			'active' => 1,
			'value' => 0,
			'code' => 'app.version.enabled',
		]);

		(new \Telenok\System\Setting())->storeOrUpdate([
			'title' => ['en' => 'Backend brand', 'ru' => 'Backend brand'],
			'active' => 1,
			'value' => 'Company Co.',
			'code' => 'app.backend.brand',
		]);
	}
}
