# api-Project

## Services

### ConvertDateTime
**Endpoint:** (https://api.theunknownones.net/convertdatetime.php)

**Methods:** GET,POST

**Purpose:** Convert a given datetime within a given timzone to a datetime in other timzone.

**Gimmicks:** You can choose the format of the output and add an modifier (for example "+1 hour")

**Required parameters:**
- `from_timestamp`
  - Type: string
  - Content: the datetime which should be converted ([read this for possible formats](https://www.php.net/manual/en/datetime.formats.php))
- `from_timezone`
  - Type: string
  - Content: the timezone of `from_datetime` ([read this for possible values](https://www.php.net/manual/en/timezones.php))
- `to_timzone`
  - Type: string
  - Content: the timezone `from_datetime` should be converted to ([read this for possible values](https://www.php.net/manual/en/timezones.php))

**Optional parameters:**
- `from_format`
  - Type: string
  - Content: defines, how the output of `from_datetime` should be formated ([read this for possible values](https://www.php.net/manual/en/datetime.format.php))
  - Default: `Y.m.d H:i:s (T)`
- `to_format`
  - Type: string
  - Content: defines, how the output of `to_datetime` should be formated ([read this for possible values](https://www.php.net/manual/en/datetime.format.php))
  - Default: `Y.m.d H:i:s (T)`
- `modify`
  - Type: string
  - Content: if this param is supplied, the modifier will be applied to `from_datetime` and `to_datetime` and the resulting values will be returned additionally ([read this for possible values](https://www.php.net/manual/en/datetime.formats.relative.php))
  - Default: `(empty)`
  
**Returns:**
- Content-Type: application/json
  - at least the field `result` with the two possible values _ok_ or _error_ will be part aof the result 
- HTTP-Codes:
  - _200_ if everything is ok
  - _400_ if there is a problem with your input
    - a field `message` will be part of the result, which shows the cause of the error

**Examples:**

convertdatetime.php?`from_datetime`=27.7.2020 15:35&`from_timezone`=Europe/Berlin&`to_timezone`=UTC

```json
{
  "result": "ok",
  "from": {
    "datetime": "2020.07.27 15:35:00 (CEST)"
  },
  "to": {
    "datetime": "2020.07.27 13:35:00 (UTC)"
  }
}
```

convertdatetime.php?`from_datetime`=27.7.2020 15:35&`from_timezone`=Europe/Berlin&`to_timezone`=UTC&`from_format`=U&`to_format`=r
```json
{
  "result": "ok",
  "from": {
    "datetime": "1595856900"
  },
  "to": {
    "datetime": "Mon, 27 Jul 2020 13:35:00 +0000"
  }
}
```

convertdatetime.php?`from_datetime`=27.7.2020 15:35&`from_timezone`=Europe/Berlin&`to_timezone`=UTC&`from_format`=U&`to_format=r`&`modify`=+1 week
```json
{
  "result": "ok",
  "from": {
    "datetime": "1595856900",
    "datetime_modified": "1596461700"
  },
  "to": {
    "datetime": "Mon, 27 Jul 2020 13:35:00 +0000",
    "datetime_modified": "Mon, 03 Aug 2020 13:35:00 +0000"
  }
}
```

convertdatetime.php?`from_datetime`=27.7.2020 15:&`from_timezone`=Europe/Berlin&`to_timezone`=UTC
```json
{
  "result": "error",
  "message": "Can not parse datetime '27.7.2020 15:'."
}
```
