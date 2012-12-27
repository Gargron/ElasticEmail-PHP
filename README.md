# ElasticEmail PHP class

A quite minimalistic, but easy to deploy PHP class for [ElasticEmail](http://elasticemail.com).

## Usage

    $email = new ElasticEmail\Email('username', 'apikey12345', array(
    	'from'      => 'notifications@domain.com',
    	'from_name' => 'Domain.com notificator'
    ));

    $email->send('receiver@gmail.com', 'Hello there!', "Hello!\n\nYou are awesome.\n\nCheers");

## License

Released under the [MIT license](http://www.opensource.org/licenses/MIT).
