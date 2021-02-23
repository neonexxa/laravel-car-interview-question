## Run Test

```
./vendor/bin/phpunit
```

### Sample Query (Refer Features Test For Further Details)

#### Q1

```
/api/appointments??is_disable_pagination=1&workshop_ids=97
```

#### Q2

Post to api below: 
```
/api/appointments
```
with params
```
'workshop_id', 'car_id', 'start_time', 'end_time'
```

#### Q3

```
/api/workshops?sortType=nearest&is_available=1&start_time=900&end_time=1000&latitude=3.221090&longitude=101.724741
```