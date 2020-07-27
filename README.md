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
  - Content: defines, how the output of `from_datetime` should be formated ([read this for possible values](https://www.php.net/manual/en/function.date.php))
  - Default: `Y.m.d H:i:s (T)`
- `to_format`
  - Type: string
  - Content: defines, how the output of `to_datetime` should be formated ([read this for possible values](https://www.php.net/manual/en/function.date.php))
  - Default: `Y.m.d H:i:s (T)`
- `modify`
  - Type: string
  - Content: if this param is supplied, the modifier will be applied to `from_datetime` and `to_datetime` and the resulting values will be returned additionally
  - Default: `(empty)`
  
**Returns:**
- Content-Type: application/json
  - at least the field `result` with the two possible values _ok_ or _error_ will be part aof the result 
- HTTP-Codes:
  - _200_ if everything is ok
  - _400_ if there is a problem with your input
    - a field `message` will be part of the result, which shows the cause of the error

**Examples:**

https://api.theunknownones.net/convertdatetime.php?from_datetime=27.7.2020%2015:35&from_timezone=Europe/Berlin&to_timezone=UTC

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
