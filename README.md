#### Magento 2 module for Zero downtime deployment.

Module prevents showing database versions exception when you pull new code to the server.
The best idea is to use CI/CD with Docker/Kubernetes.
Suggested deployment:

- get docker base image
- run php bin/magento setup:upgrade on destination database
- run php bin/magento setup:di:compile and php bin/magento setup:static-content:deploy on build container
- deploy new container

NOTICE: If new version contain new classes (for example in EAV) or changed logic, Magento could behave unpredictable.
You are using this module at your own risk!

Inspiration: https://medium.com/@egorshytikov/magento-0-downtime-deployment-2-9a6727efd57a

Thanks Yegor Shytikov!

This module will work only with Magento 2.3.0 and higher


# **Install**

### Git
- Locate the **/app/code** directory which should be under the magento root installation.
- If the **code** folder is not there, create it.
- Create a folder **Monogo** inside the **code** folder. 
- Change to the **Monogo** folder and clone the Git repository (https://github.com/MonogoPolska/monogo-m2-zero-downtime-deployment.git) into **Monogo** specifying the local repository folder to be **OptimizeDatabase** 
e.g. 

``` git clone https://github.com/MonogoPolska/monogo-zero-downtime-deployment ZeroDowntimeDeployment```

### Composer
```composer require monogo/zero-downtime-deployment```

### Magento Setup
- Run Magento commands

```php bin/magento setup:upgrade```

```php bin/magento setup:di:compile```

```php bin/magento setup:static-content:deploy```

# **App Configuration Options**

Go to Stores->Configuration->Monogo->Zero downtime deployment

- Enable module **Default value is 0 (No)**
- Enable logger **Default value is 0 (No)**

# **TODO**
- Tests
