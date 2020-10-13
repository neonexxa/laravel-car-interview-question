# Laravel Car Interview Question

The Car Solutions own multiple workshops located at multiple locations with different working hours.

The company is currently building a platform for the it's admin staff in order to schedule booking appointments with different workshops.

Whenever a client called in to schedule for an appointment. The admin staff needs to be able to check the availability of the workshops, make recommendations and also create a new appointment.

The staff at the workshop will need to have access to the appointments on daily basis in order to prepare the required parts and tools.

## Your task

Create endpoints that allows the admin staff to:

1. List down all the appointments for all workshops with ability to filter by each workshop

2. Schedule an appointment based on client's request

  - It should be able to create a new appointment based on given information

  - Other than that, it should also detect the available of the workshop and prevent appointments with same time being created twice.

3. Recommend the workshops based on the availability and the locations

  - The endpoint should be able to recommend workshops based on

    1. Availability (Filter the time that the workshop is not available)

    2. Location (Sort the workshop based on the distance)

## Notes
- Feel free include any assumptions or notes that you have

- Please include any instructions or guides that you have in order for us to test the work that you have done

- We liked tests, include tests in your code will be advantageous

## Setup
- Please refer to https://laravel.com/docs/6.x/installation on how to set it up and running in you machine

- Once you have the environment up, run `scripts/setup` to setup the database and run the migration

- In order to seed the data, please run `php artisan db:seed`

## Send the answer back to us

1. Checkout and work on your branch

2. Commit as you progress

3. Once you are done, generate the patch file by using

```
git format-patch develop
```

4. Send the patch file back to us
