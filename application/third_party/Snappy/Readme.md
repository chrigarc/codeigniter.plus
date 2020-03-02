# Snappy single wrapper

This solution is only for projects where is not allowed the composer tool.

All code source is from the respective authors

I only writed a single wrapper by codeigniter integration.


## Repositories
* https://github.com/KnpLabs/snappy
* https://github.com/php-fig/log
* https://github.com/symfony/process

## Important

This solution only works with PHP 5.6 or less

## Use mode
```php
$this->load->add_package_path(APPPATH.'third_party/Snappy');
$this->load->library('Snappy', null, 'pdf');
$content='<p>Some content</p>';
echo $this->pdf->getOutputFromHtml($content);
```
