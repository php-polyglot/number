# polyglot/number

> A [polyglot](https://packagist.org/packages/polyglot/) number.

# Install

```shell
composer require polyglot/number:^1.0
```
# Using

```php
$number = new \Polyglot\Number\Number('1.20050c3');

$n = $number->number(); // returns 1200.5 (absolute value)
$i = $number->integer(); // returns 1200 (integer digits)
$v = $number->fractionDigits(); // returns 2 (number of visible fraction digits, with trailing zeros)
$w = $number->fractionDigits(false); // returns 1 (number of visible fraction digits, without trailing zeros)
$f = $number->fraction(); // returns 50 (visible fraction digits, with trailing zeros, expressed as an integer)
$t = $number->fraction(false); // returns 5 (visible fraction digits, without trailing zeros, expressed as an integer)
$c = $number->exponent(); // returns 3 (compact decimal exponent value)
```
