<?php

function parseText($text){
    // Код может быть в начале строки, может быть в конце строки, может быть по середине
// Код не может находится между цифр и перед указателем на валюты(р,руб,рублей)
// Если сумма больше или равна 5000 Код - 5-ти значный, если меньше то 4-х
    preg_match('/^([0-9]{4,5}(?!([\d.,])|(\s?[Рр])))|((?<=\D)[0-9]{4,5}(?!([\d.,])|(\s?[Рр])))|((?<=\D)([0-9]{4,5}))$/u',$text,$code);
// Изучил вашу форму: длина кошелька от 13 до 16 символов.
// Нашел в документации Яндекс Денег что Кошелек может быть от 11 до 20 цифр https://kassa.yandex.ru/tech/payout/wallet.html
// Также вычитал что яндекс кошельки начинаются на 41001
    preg_match('/41001[0-9]{6,15}/', $text,$wallet);
// Должен заканчиваться на р. р руб. рублей с большой или маленькой буквы.
// Максимальная сумма 10000 значит знаков перед разделеителем от 1 до 5
    preg_match('/[0-9]{1,5}([,.][0-9]{1,2})?(?=(\s?[Рр]))/u', $text,$amount);

    $data = ['code' => '', 'amount' => '', 'wallet' => '' ];

    if(isset($code[0]) && !empty($code[0])){
        $data['code'] = $code[0];
    }
    if(isset($amount[0]) && !empty($amount[0])){
        $data['amount'] = $amount[0];
    }
    if(isset($wallet[0]) && !empty($wallet[0])){
        $data['wallet'] = $wallet[0];
    }

    return $data;
}

$text = $_GET['text'];
$data = parseText($text);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form>
    <textarea name="text" rows="7" cols="42"><?php echo $_GET['text']; ?></textarea>
    <button type="submit">Apply</button>
</form>
<p>
    Код: <?php print_r($data['code']); ?>
</p>
<p>
    Сумма <?php print_r($data['amount']) ?>
</p>
<p>
    Кошелек <?php print_r($data['wallet']) ?>
</p>
</body>
</html>
