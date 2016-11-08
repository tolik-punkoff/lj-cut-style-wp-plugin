<?php

/*
Plugin Name: LJ-cut style cut
Description: Add Livejournal-like Cut Shortcode [lj-cut text="Cut text..." unicancor="unically_ancor"] HIDDEN TEXT [/lj-cut]. No CSS, no Javascript. Плагин добавляет шорткод, эмулирующий ЖЖ-подобный кат с дополненным функционалом. Использование: [lj-cut] Текст под катом [/lj-cut]. Необязательные параметры: text="Текст ссылки на подкат", по-умолчанию 'Read more...', unicancor="unically-ancor", позволяет задать уникальный якорь на ту область страницы, на которую ведет ссылка ката. Использовать только латинские буквы и знаки - и _. Якорь должен быть уникальным. По-умолчанию используются якоря в стиле ЖЖ (cutid1, cutid2 и т.д.). Возможно использовать шорткод несколько раз в одном посту. Плагин не требует Javascript и не использует специальные возможности CSS.
Version: 0.0.1b
Author: Tolik Punkoff
Author URI: http://tolik-punkoff.com/
License: any
 */


function ljcut_shortcode($atts, $content=null)
{			
	static $cutid=0; //номер текущего cutid в посту
	static $oldplink=''; //предыдущий permalink 
	//устанавливаем атрибут text, как в ЖЖ
	extract(shortcode_atts(array(
	      'text' => 'Read more...',
	      'unicancor' => '',
	), $atts));

	$plink=get_permalink(); //получаем URL текущего поста
	$clink=get_bloginfo('url').$_SERVER["REQUEST_URI"]; //URL текущей страницы

	if ($oldplink!=$plink) //пост новый, надо начать отсчет cutid заново (с 1)
	{
		$cutid=1;
		$oldplink=$plink; //и сохранить текущий 
	}
	else //мы все еще обрабатываем старый пост
	{
		$cutid++; //прибавляем значение cutid
	}

	if ($plink==$clink)
	{
		//мы в теле поста, cut надо раскрыть и вставить якорь
		if ($unicancor=='') //если якорь не задан, используем cutidn
		{
			$ret='<a name="cutid' . $cutid . '"></a> ' .$content;
		}
		else
		{
			$ret='<a name="' . $unicancor . '"></a> ' .$content;
		}
	}
	else
	{
		//мы на одной из страниц, но не в самом посту
		//надо установить ссылку на пост и на нужный якорь в посту
		if ($unicancor=='') //если якорь не задан, используем cutidn
		{
			$ret='<a class="more-link" ' . 'href="' . $plink . '#cutid' . $cutid .
			 '">' . $text . '</a>';
		}
		else
		{
			$ret='<a class="more-link" ' . 'href="' . $plink . '#' . $unicancor .
			 '">' . $text . '</a>';
		}
	}
	
	return $ret;
}

add_shortcode ('lj-cut','ljcut_shortcode');

?>