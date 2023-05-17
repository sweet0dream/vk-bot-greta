<?php
	function vk_msg_send($peer_id, $text, $attach = false) {
	    $request_params = array(
	        'message' => $text, 
	        'peer_id' => $peer_id,
	        'access_token' => "vk1.a.DjV2cIr32BV4_xbYi44dahy17PXIilzPNkJqsenzp-5wKC44T91sdb_GKQmsf5OVxyc3_ZCbaN7KJh9pBvpV6qa7LS436vzOpn_7R0UNXYcRLiEndfo7x9vDRUuIuzhC5wAPyCZhOv0aA76NV1LsQiFtqJLr3Vg13F9hLBteP3UkFyUgTV_gSzlxMU_5ZAl6o-ONt_IAYcMAoTu9YBUzJA",
	        'v' => '5.87'
	    );
	    if($attach) {
	        $request_params['attachment'] = $attach;
	    }
	    $get_params = http_build_query($request_params);
	    
	    file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
	}

	function format_num($number, $suffix) {
	    $keys = array(2, 0, 1, 1, 1, 2);
	    $mod = $number % 100;
	    $suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
	    return $suffix[$suffix_key];
	}

	function getUserVK($vk_id, $added_param = false) {
		$param = [
			'user_ids' => $vk_id,
			'access_token' => 'vk1.a.DjV2cIr32BV4_xbYi44dahy17PXIilzPNkJqsenzp-5wKC44T91sdb_GKQmsf5OVxyc3_ZCbaN7KJh9pBvpV6qa7LS436vzOpn_7R0UNXYcRLiEndfo7x9vDRUuIuzhC5wAPyCZhOv0aA76NV1LsQiFtqJLr3Vg13F9hLBteP3UkFyUgTV_gSzlxMU_5ZAl6o-ONt_IAYcMAoTu9YBUzJA',
			'v' => '5.131'
		];
		if($added_param && is_array($param)) {
			$param = array_merge($param, $added_param);
		}
		$result = json_decode(file_get_contents('https://api.vk.com/method/users.get?'.http_build_query($param)), true)['response'][0];
		return is_array($result) ? $result : false;
	}

	function DBconnect($data, $table) {
	    if(is_array($data)) {
	        $db = new PDODb([
	            'type' => 'mysql',
	            'host' => 'localhost',
	            'username' => 'user_0bot0', 
	            'password' => 'Trash2012!',
	            'dbname'=> 'user_0bot0'
	        ]);
	        if(isset($data['where']) && is_array($data['where'])) {
	            foreach($data['where'] as $k => $v) {
	                $db->where($k, $v);
	            }
	        }
	        if($data['type'] == 'insert') {
	            return $db->insert($table, $data['value']);
	        } elseif($data['type'] == 'update') {
	            return $db->update($table, $data['value']);
	        } elseif($data['type'] == 'delete') {
	            return $db->delete($table);
	        }  elseif($data['type'] == 'select') {
	            return $db->get($table);
	        } elseif($data['type'] == 'one') {
	            return $db->getOne($table);
	        } elseif($data['type'] == 'count') {
	            return $db->getValue($table, 'COUNT(*)');
	        }
	    } else {
	        return false;
	    }
	}

	function isLinkUser($name) {
	    preg_match('/\[(.*)\|(.*)\]/', $name, $name);
	    if(!empty($name)) {
	    	unset($name[0]); unset($name[2]);
	    	$vk_id = str_replace('id', '', $name[1]);
	    }
	    return isset($vk_id) ? $vk_id : false;
	}

	function createLinkUser($user_id) {
		$user = DBconnect(['type' => 'one', 'where' => ['vk_id' => $user_id]], 'users_nicks');
		if(!empty($user) && isset($user['id'])) {
			return "[id".$user_id."|".$user['nick']."]";
		} else {
			return "[id".$user_id."|".getUserVK($user_id)['first_name']."]";
		}
	}

	function isAdmin($user_id) {
		foreach(json_decode(file_get_contents('https://api.vk.com/method/messages.getConversationMembers?peer_id=2000000001&v=5.131&access_token=vk1.a.DjV2cIr32BV4_xbYi44dahy17PXIilzPNkJqsenzp-5wKC44T91sdb_GKQmsf5OVxyc3_ZCbaN7KJh9pBvpV6qa7LS436vzOpn_7R0UNXYcRLiEndfo7x9vDRUuIuzhC5wAPyCZhOv0aA76NV1LsQiFtqJLr3Vg13F9hLBteP3UkFyUgTV_gSzlxMU_5ZAl6o-ONt_IAYcMAoTu9YBUzJA'), true)['response']['items'] as $user) {
			if($user['member_id'] == $user_id) {
				return isset($user['is_admin']) && $user['is_admin'] == true ? true : false;
			}
		}
	}

	function getRules() {
	    return "ПРАВИЛА УЧАСТНИКОВ:\n\n1. Не флудить и не спамить - флудом/спамом считается бесконтрольная отправка сообщения лишённых смысла, смайлов и стикеров.\nНаказание: исключение из чата на срок от 2 часов до суток на усмотрение админов чата.\n2. Провокация конфликта равно как и сам конфликт.\nНаказание: исключение из чата на сутки провокатора конфликта или всех действующих лиц конфликта - на усмотрение админов чата.\n\nПРАВИЛА АДМИНОВ:\n\n1. Количество админов определяет главный админ. Выборы админов чата происходят на основании, созданного главным админом чата, голосования. Кандидатуры на админов чата выбираются главным админом, если необходимо, по согласованию с создателем и/или на общем голосовании по списку прентендентов.\n2. Бан участников по пунктам правил осуществляется самостоятельно, в спорных моментах необходимо согласование создателя. Перед исключением из чата необходимо отправить в чат сообщение следующего вида: «@ССЫЛКА_НА_УЧАСТНИКА, исключается из чата на СРОК_ИСКЛЮЧЕНИЯ. Причина: КРАТКОЕ_ОПИСАНИЕ_НАРУШЕНИЯ»\n3. Злоупотребление полномочиями админа (такие как: исключение участников без причины) наказывается снятием полномочий.\n\nСоздатель: @id21577652 - Кирилл\nГлавный админ: @id581965553 - Настя\nАдмин: @id559920769 - Миша";
	}

	function getPrivet($vk_id) {
	    $termPrivet = [
	        'и тебе не хворать, самодовольная скотина',
	        'ну, здравствуй, жертва неудачного аборта',
	        'салют, кусок дебильного мяса',
	        'эх, не видеть быть тебя сто лет',
	        'для тебя не «Привет», а «Здравствуйте, Гретель Кирилловна»',
	        'привет-привет, ты когда, гавно, долг мне отдашь?!',
	        'привет, с тебя килограмм конфет',
	        'здарова, чувачело)',
	        'о, привет) Какая нечистая тебя притащила?',
	        'вспомнишь гавно, вот и оно. Здравствуй)'
	    ];
	    return createLinkUser($vk_id).', '.$termPrivet[rand(0,9)];
	}

	function getFas($name, $user_from) {
		$termFas = [
			1 => [
				'word0' => ['конченная', 'обрыганная', 'зализанная', 'ментовская', 'жоподрищенская', 'безмозглая', 'хуёвая', 'обосанная', 'всратая', 'слюнявая'],
				'word1' => ['курица', 'овца', 'коза', 'тупица', 'ублюдиха', 'чмошница', 'жополизка', 'глиста', 'лесбуха', 'гавнялка']
			],
			2 => [
				'word0' => ['конченный', 'обрыганный', 'зализанный', 'ментовской', 'жоподрищенский', 'безмозглый', 'хуёвый', 'обосанный', 'всратый', 'слюнявый'],
				'word1' => ['петух', 'баран', 'козлина', 'тупица', 'ублюдок', 'чмошник', 'жополиз', 'глист', 'пидрило', 'гавнило']
			],
			'do' => ['я тебя на бутылку посажу', 'я тебе на клыка накину', 'я тебе очко порву', 'я тебе голову разобью', 'я тебе пасть порву', 'я тебя разчленю', 'я тебя собакам скормлю', 'тебе пиздец', 'щемись теперь', 'я твой дом труба шатала'],
			'stop' => ['Ты на кого свою пасть открыл', 'Ничего не попутал', 'Гавна чтоль въебал', 'Завали пачку', 'Ротик на офф', 'Запечатай хлеборезку', 'Иди козе в трещину', 'Завалил свою поганную дырку', 'Рот не открывай - воняет', 'Эх пизды получишь']
		];
	    if($vk_id = isLinkUser($name)) {
	    	$user = getUserVK($vk_id, ['fields' => 'sex']);
	    	$gender = getUserVK($user_from, ['fields' => 'sex'])['sex'];
	        if(isAdmin($vk_id)) {
	            return [
					'message' => $termFas['stop'][rand(0,9)].', '.$termFas[$gender]['word0'][rand(0,9)].' '.$termFas[$gender]['word1'][rand(0,9)].', админов нельзя кусать!',
					'attach' => 'photo-'.['216901410_457239037', '216901410_457239038', '216901410_457239039', '216901410_457239040', '216901410_457239041', '216901410_457239042', '216901410_457239043', '216901410_457239044', '216901410_457239045', '216901410_457239046'][rand(0,9)]
				];
	        } else {
	            return [
					'message' => createLinkUser($vk_id).', ты '.$termFas[$user['sex']]['word0'][rand(0,9)].' '.$termFas[$user['sex']]['word1'][rand(0,9)].', '.$termFas['do'][rand(0,9)],
					'attach' => 'photo-'.['216901410_457239037', '216901410_457239038', '216901410_457239039', '216901410_457239040', '216901410_457239041', '216901410_457239042', '216901410_457239043', '216901410_457239044', '216901410_457239045', '216901410_457239046'][rand(0,9)]
				];
	        }
		} else {
			return [
				'message' => ['Слышь', 'Эй'][rand(0,1)].', ты '.$termFas[$user['sex']]['word0'][rand(0,9)].' '.$termFas[$user['sex']]['word1'][rand(0,9)].', я не поняла кого кусать?',
				'attach' => 'photo-'.['216901410_457239024', '216901410_457239025', '216901410_457239026'][rand(0,2)]
			];
		}
	}

	function getLiz($name) {
		if($vk_id = isLinkUser($name)) {
			$user = getUserVK($vk_id, ['fields' => 'sex']);
			$termLiz = [
				1 => [
					'word0' => ['сладкая', 'хорошая', 'замечательная', 'умопомрачительная', 'неотразимая', 'красивая', 'чёткая', 'прелестная', 'милая', 'офигенная'],
					'word1' => ['конфетка', 'розочка', 'косточка', 'малышка', 'принцесса', 'кошечка', 'зайка', 'белочка', 'птичка', 'звездочка' ]
				],
				2 => [
					'word0' => ['сладкий', 'хорошенький', 'замечательный', 'умопомрачительный', 'неотразимый', 'красивый', 'чёткий', 'прелестный', 'милый', 'офигенный'],
					'word1' => ['чупачупс', 'цветок', 'десерт', 'малыш', 'принц', 'котенок', 'зайчонок', 'бельчонок', 'воробушек', 'одуванчик' ]
				],
				'heart' => ['&#128150;', '&#10084;', '&#128149;', '&#129392;', '&#128158;', '&#128157;', '&#128151;', '&#9829;', '&#128152;', '&#128140;']
			];
			$smileHearts = $termLiz['heart'][rand(0,9)]."".$termLiz['heart'][rand(0,9)]."".$termLiz['heart'][rand(0,9)];
			return [
				'message' => createLinkUser($vk_id).', ты '.$termLiz[$user['sex']]['word0'][rand(0,9)].' '.$termLiz[$user['sex']]['word1'][rand(0,9)].' '.$smileHearts,
				'attach' => 'photo-'.['216901410_457239027', '216901410_457239028', '216901410_457239029', '216901410_457239030', '216901410_457239031', '216901410_457239032', '216901410_457239033', '216901410_457239034', '216901410_457239035', '216901410_457239036'][rand(0,9)]
			];
		} else {
			return [
				'message' => 'Я не поняла кого лизнуть нужно?',
				'attach' => 'photo-'.['216901410_457239024', '216901410_457239025', '216901410_457239026'][rand(0,2)]
			];
		}
	}

	function getJoke($category) {
	    $ch = curl_init('http://rzhunemogu.ru/RandJSON.aspx?CType='.$category);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_POST, false);
	        curl_setopt($curl, CURLOPT_VERBOSE, 1);
	        curl_setopt($ch, CURLOPT_HEADER, 1);
	        $tmpResult = curl_exec($ch);
	        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	        curl_close($ch);
	    $tmpHeaders = substr($tmpResult, 0, $header_size);
	    $postResult = substr($tmpResult, $header_size);
	    $headers = array();
	    foreach(explode("\n", $tmpHeaders) as $header) {
	        $tmp = explode(":",trim($header),2);
	        if (count($tmp)>1) {
	            $headers[strtolower($tmp[0])] = trim(strtolower($tmp[1]));
	        }
	    }
	    $encoding="utf-8"; //default
	    if (isset($headers['content-type'])) {
	        $tmp = explode("=", $headers['content-type']);
	        if (count($tmp)>1) $encoding = $tmp[1];
	    }
	    if ($encoding != "utf-8") $postResult = iconv($encoding, "UTF-8", $postResult);
	    $result = json_decode($postResult)->content;
	    return isset($result) ? $result : getJoke($category);
	}

	function getRandomAnswer($question) {
	    unset($question[0]); unset($question[1]);
	    $term_answer = [
	        'Да, да и, ещё раз, точно - да.',
	        'Я могу предположить, что скорее всего - да.',
	        'Не берусь утверждать, но вполне возможно.',
	        'Меня кидает в сомнение, что возможно - нет.',
	        'Не-не, точно нет.',
	        'Ни в коем случае.'
	    ];
	    return "Ты спросил: ".implode(' ', $question)."\n\nМой ответ: ".$term_answer[rand(0, count($term_answer)-1)];
	}

	function getWeather() {
	    $src = json_decode(file_get_contents('https://api.openweathermap.org/data/2.5/weather?lat=51.592406&lon=45.960720&lang=ru&units=metric&appid=8e9b7b5ae1d1ef16858bcf5e37d585e1'), true);
	    $temp = floor($src['main']['temp']).'°';
	    $desc = $src['weather'][0]['description'];
	    return 'В нашем дворе сейчас '.$temp.', '.$desc;
	}

	function getCUEFA($subject) {
	    $term = [
	      'k' => 'камень',
	      'n' => 'ножницы',
	      'b' => 'бумага'
	    ];
	    
	    $q_key = in_array($subject, $term) ? array_search($subject, $term) : false;
	    $a_key = array_keys($term)[rand(0,2)];
	    
	    if($q_key != false) {
	        $text = 'Я загадала: '.$term[$a_key];
	        if($q_key == $a_key) {
	            $text .= ' - ничья, заново &#128579;';
	        } else {
	            if($q_key == 'k' && $a_key == 'n' || $q_key == 'n' && $a_key == 'b' || $q_key == 'b' && $a_key == 'k') {
	                $text .= ' - я проиграла &#128528;';
	            } elseif($q_key == 'n' && $a_key == 'k' || $q_key == 'b' && $a_key == 'n' || $q_key == 'k' && $a_key == 'b') {
	                $text .= ' - я выиграла &#128523;';
	            }
	        }
	    } else {
	        $text = 'Ты идиот, в игре только три предмета: камень, ножницы, бумага &#128511;';
	    }
	    return $text;
	}

	function getHumiliate($name) {
	    if($vk_id = isLinkUser($name)) {
	        if(isAdmin($vk_id)) {
	            $message['text'] = 'Ты, гавно собачье, на кого свой рот поднял?';
	        } else {
	            $message = [
	                'text' => createLinkUser($vk_id).', ублюдок, мать твою, а ну, иди сюда, говно собачье, а? Что, сдуру решил ко мне лезть? Ты, засранец вонючий, мать твою, а? Ну, иди сюда, попробуй меня трахнуть – я тебя сам трахну, ублюдок, онанист чертов, будь ты проклят! Иди, идиот, трахать тебя и всю твою семью! Говно собачье, жлоб вонючий, дерьмо, сука, падла! Иди сюда, мерзавец, негодяй, гад! Иди сюда, ты, говно, жопа!',
	                'attach' => 'video-90074084_456240213'
	            ];
	        }
	    } else {
	        $message['text'] = 'Кого унижать надо, мудак? Линк надо.';
	    }

	    return isset($message) ? $message : false;
	}

	function getDestroy($name) {
	    if($vk_id = isLinkUser($name)) {
	        if(isAdmin($vk_id)) {
	            $message['text'] = 'Ты, гавно собачье, на кого свой рот поднял?';
	        } else {
	            $message = [
	                'text' => createLinkUser($vk_id).", Говно, залупа, пенис, хер, давалка, хуй, блядина\nГоловка, шлюха, жопа, член, еблан, петух… Мудила\nРукоблуд, ссанина, очко, блядун, вагина\nСука, ебланище, влагалище, пердун, дрочила\nПидор, пизда, туз, малафья\nГомик, мудила, пилотка, манда\nАнус, вагина, путана, педрила\nШалава, хуило, мошонка, елда…\nРаунд!",
	                'attach' => 'video798309_171632975'
	            ];
	        }
	    } else {
	        $message['text'] = 'Кого уничтожать, дебила? Линк надо.';
	    }

	    return isset($message) ? $message : false;
	}

	function showNick($id) {
		if($id == 'all') {
			foreach(DBconnect(['type' => 'select'], 'users_nicks') as $user) {
				$u = getUserVK($user['vk_id']);
				$message[] = $u['first_name'].' '.$u['last_name'].', ник: '.$user['nick'];
			}
			if(isset($message) && !empty($message)) {
				$message = implode("\r\n", $message);
			}
		} elseif($u = getUserVK($id)) {
			$nick = DBconnect(['type' => 'one', 'where' => ['vk_id' => $u['id']]], 'users_nicks')['nick'];
			$message = "У ".$u['first_name']." ".$u['last_name'];
			if(isset($nick)) {
				$message .= " ник: ".$nick;
			} else {
				$message .= " ник не назначен";
			}
		} else {
			$message = 'Ты не указал ссылку на участника, долбоёб';
		}
		return $message;
	}

	function setNick($param) {
		if($user = getUserVK($param['user'])) {
			$message[] = $user['first_name'].' '.$user['last_name'];
			if($nick = DBconnect(['type' => 'one', 'where' => ['vk_id' => $user['id']]], 'users_nicks')['nick']) {
				$message[] = 'Старый ник был: '.$nick;
			}
			if($nick) {
				DBconnect(['type' => 'update', 'where' => ['vk_id' => $user['id']], 'value' => ['nick' => $param['nick']]], 'users_nicks');
			} else {
				DBconnect(['type' => 'insert', 'value' => ['vk_id' => $user['id'], 'nick' => $param['nick']]], 'users_nicks');
			}
			$message[] = 'Новый ник: '.$param['nick'];
		}

		return implode('. ', $message);
	}












	/*function getWho($user_id) {
		$user = DBconnect(['type' => 'one', 'where' => ['vk_id' => $user_id]], 'users_id');
		$jambs = DBconnect(['type' => 'select', 'where' => ['user_id' => $user['id']]], 'users_jambs');
		$photos = DBconnect(['type' => 'select', 'where' => ['user_id' => $user['id']]], 'users_photos');
		$message = "Участник чата: ".$user['first_name']." ".$user['last_name'];
		if($user['nick'] != '') {
			$message .= ", погремуха: ".$user['nick'];
		}
		if($user['is_admin'] == 1) {
			$message .= ", админ чата.";
		}
		if(!empty($jambs) && isset($jambs[0])) {
			$message .= "\n\nЗа ".[1 => 'ней', 2 => 'ним'][$user['gender']]." есть ".count($jambs)." ".format_num(count($jambs), ['косяк', 'косяка', 'косяков']).".\n";
			for($i = 0; $i < count($jambs); $i++) {
				$num = $i+1;
				$message .= "\n".($num).") ".$jambs[$i]['jamb'];
			}
		}
		if(empty($photos) && !isset($photos[0])) {
			$photos = 'photo'.json_decode(file_get_contents('https://api.vk.com/method/users.get?user_ids='.$user['vk_id'].'&fields=photo_id&access_token=vk1.a.DjV2cIr32BV4_xbYi44dahy17PXIilzPNkJqsenzp-5wKC44T91sdb_GKQmsf5OVxyc3_ZCbaN7KJh9pBvpV6qa7LS436vzOpn_7R0UNXYcRLiEndfo7x9vDRUuIuzhC5wAPyCZhOv0aA76NV1LsQiFtqJLr3Vg13F9hLBteP3UkFyUgTV_gSzlxMU_5ZAl6o-ONt_IAYcMAoTu9YBUzJA&v=5.131'), true)['response'][0]['photo_id'];
		} else {
			$img = [];
			foreach($photos as $k => $v) {
				$img[] = 'photo-'.$v['photo'];
			}
			$photos = implode(',', $img);
		}
		return isset($message) ? [
			'message' => $message,
			'attach' => $photos
		] : false;
	}

	function getNick($user_id = false) {
	    if($user_id) {
	        $user = DBconnect(['type' => 'one', 'where' => ['vk_id' => $user_id]], 'users_id');
	        $message = createLinkUser($user['vk_id'], ['first_name']);
	        if(isset($user['nick']) && $user['nick'] != '') {
	        	$message .= ", ник: ".$user['nick'].".";
	        } else {
	        	$message .= ", погремухи нет.";
	        }
	        if($user['who_set_nick'] != 0) {
	        	$message .= "\n\nИзменить ник может админ чата ".createLinkUser($user['who_set_nick']);
	        } else {
	        	$message .= "\nМожно изменить.\n\nКоманда:\nГрета ник НОВЫЙНИК";
	        }
	    } else {
	        $message = [];
	        foreach(DBconnect(['type' => 'select'], 'users_id') as $user) {
	            if(isset($user['nick']) && $user['nick'] != '') {
	                $message[] = createLinkUser($user['vk_id'], ['first_name'])." -> ".$user['nick'];
	            }
	        }
	        if(!empty($message)) {
	            $message = "Погремухи в чате:\n".implode("\n", $message)."\n\nПРОСМОТР СВОЕГО НИКА:\nГрета ник\n\nИЗМЕНЕНИЕ НИКА:\nГрета ник НОВЫЙНИК";
	        }
	    }
	    return isset($message) ? $message : false;
	}

	function setNick($nick, $user_id, $admin_id = false) {
	    $user = DBconnect(['type' => 'one', 'where' => ['vk_id' => $user_id]], 'users_id');
	    if($user['who_set_nick'] != 0) {
	    	if($admin_id && $admin_id == $user['who_set_nick']) {
	    		if(DBconnect(['type' => 'update', 'where' => ['id' => $user['id']], 'value' => ['nick' => $nick]], 'users_id')) {
					$message = "Админ ".createLinkUser($user['who_set_nick'], ['first_name'])." изменил ник для ".createLinkUser($user_id, ['first_name', 'last_name']).".\n".[1 => 'Её', 2 => 'Его'][$user['gender']]." новый ник в чате: ".$nick;
				}
	    	} elseif($admin_id && $admin_id != $user['who_set_nick']) {
	    		$message = "Я не могу изменить ник для ".createLinkUser($user['vk_id'], ['first_name', 'last_name']).".\nЗапрет установлен админом: ".createLinkUser($user['who_set_nick'], ['first_name']);
	    	} else {
	    		$message = createLinkUser($user['vk_id'], ['first_name']).", ты не можешь изменить свой ник.\nЗапрет установлен админом: ".createLinkUser($user['who_set_nick'], ['first_name'])."\nПопроси его снять запрет или установить тебе новый ник.";
	    	}
	    } elseif($user['who_set_nick'] == 0) {
	    	if(DBconnect(['type' => 'update', 'where' => ['id' => $user['id']], 'value' => ['nick' => $nick]], 'users_id')) {
				$message = createLinkUser($user['vk_id'], ['first_name']).", я изменила твой ник. Твой новый ник в чате: ".$nick;
			}
	    }
	    return isset($message) ? $message : false;
	}

	function setChangeNick($changeVal, $user_id, $admin_id) {
		if(isAdmin($user_id)) {
			$message = "Админы не могут запрещать или разрешать менять ники друг другу.";
		} else {
			$user = DBconnect(['type' => 'one', 'where' => ['vk_id' => $user_id]], 'users_id');
			if($user['who_set_nick'] == 0 || $user['who_set_nick'] == $admin_id) {
				if($changeVal == 1) $changeVal = $admin_id;
				if(DBconnect(['type' => 'update', 'where' => ['id' => $user['id']], 'value' => ['who_set_nick' => $changeVal]], 'users_id')) {
					$message = "Админ ".createLinkUser($admin_id, ['first_name'])." ".($changeVal == 0 ? 'разрешил' : 'запретил')." участнику ".createLinkUser($user['vk_id'], ['first_name', 'last_name'])." изменять свой ник";
				}
			} elseif($user['who_set_nick'] != $admin_id) {
				$message = "Запрет смены ника для участника ".createLinkUser($user['vk_id'], ['first_name', 'last_name'])." установлен другим админом: ".createLinkUser($user['who_set_nick'], ['first_name']);
			} else {
				$message = "Запрет смены ника для участника ".createLinkUser($user['vk_id'], ['first_name', 'last_name'])." установлен админом: ".createLinkUser($user['who_set_nick'], ['first_name']);
			}
		}
		return isset($message) ? $message : false;
	}*/
?>