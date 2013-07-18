# accounting

Accounting lib is your modern comp(t)anion for general accounting in PHP.

## Validators

### Vat

The lib provides 2 Vat validators:
- VatValidator: for all knwon Vat numbers systems.
- ViesValidator: for european Vat numbers only.


## Formatters

### Vat

TODO

### IBAN

TODO

## Operations

### Pain point

Accounting operations are sometimes subjected to bugs.
Why? Because they're made of sets of arithmetic computations, but if they use mathematics,
it's with their own rules about rounding results, managing currencies specific number of fraction digits and so on... this is why you should use this lib ;)

### Benefits

- Computations are made in the exact order you want (damn, don't compute this tax before this operation!).
- Number of fractions of each currency is respected.
- Rounding rules are applied the right way in each computation.
- Last but not least, you can develop you own sets of operations and apply them like computation templates. You just have to decorate your results after that, pretty easy ;)

### Example

```php
$resultsSet = new OperationSetResult(array(
    'discount_rate' => new Percentage(10),   // Our special discount rate for that lucky guy
    'vat_rate'      => new Percentage(19.6), // French VAT rate
));

$f  = new Factory();
$os = new OperationSet(
    array(
        'price_discounted' => new Operation(
            new Reference\Reference('price_excl_taxes'),
            Operation::MINORATE,
            new Reference\Reference('discount_rate')
        ),
        'price_incl_taxes' => new Operation(
            $f->value(new Reference\Reference('price_discounted')),
            Operation::MAJORATE,
            new Reference\Reference('vat_rate')
        ),
    ),
    $resultsSet
);

$os
    ->getResultsSet()
    ->add(new Price(100, 'EUR'), 'price_excl_taxes');
$os->compute();

echo sprintf("%s\n", $os->getResultsSet());
```

```
discount_rate: 10%
vat_rate: 19.6%
price_excl_taxes: 100€
price_discounted: 100€ minorate by 10% => 90€ \ 10€
price_incl_taxes: 90€ majorate by 19.6% => 107.64€ \ 17.64€
```

### Same thing but with Japan Yen (0 decimal fractions currency)

```php
[...]

$os
    ->getResultsSet()
    ->add(new Price(100, 'EUR'), 'price_excl_taxes');
$os->compute();

echo sprintf("%s\n", $os->getResultsSet());
```

```
discount_rate: 10%
vat_rate: 19.6%
price_excl_taxes: 100¥JP
price_discounted: 100¥JP minorate by 10% => 90¥JP \ 10¥JP
price_incl_taxes: 90¥JP majorate by 19.6% => 108¥JP \ 18¥JP
```
