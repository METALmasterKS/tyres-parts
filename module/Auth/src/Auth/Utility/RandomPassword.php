<?

namespace Auth\Utility;

class RandomPassword
{

    private $minLength;
    private $maxLength;

    public function __construct()
    {
        $this->minLength = 6;
        $this->maxLength = 8;
    }

    public function getRandomPassword()
    {
        $minLength = 6;
        $maxLength = 8;
        $rgLetters = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0');
        shuffle($rgLetters);

        $password = join('', array_slice($rgLetters, 0, mt_rand($minLength, $maxLength)));

        return $password;
    }

}

?>
