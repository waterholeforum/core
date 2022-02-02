time-ago =
  { $unit ->
    [second] Just now
    [minute] { $value } { $value ->
      [1] minute
      *[other] minutes
    } ago
    [hour] { $value } { $value ->
      [1] hour
      *[other] hours
    } ago
    [day] { $value } { $value ->
      [1] day
      *[other] days
    } ago
    [week] { $value } { $value ->
      [1] week
      *[other] weeks
    } ago
    [month] { $value } { $value ->
      [1] month
      *[other] months
    } ago
    [year] { $value } { $value ->
      [1] year
      *[other] years
    } ago
    *[other] unknown time
  }

short-time-ago =
  { $value }{ $unit ->
    [second] s
    [minute] m
    [hour] h
    [day] d
    [week] w
    [month] mo
    [year] y
    *[other] ?
  }

relative-time = { $unit ->
  [second] { time-ago }
  [minute] { time-ago }
  [hour] { time-ago }
  [day] { time-ago }
  [week] { time-ago }
  *[other] { DATETIME($date) }
}

short-time = { DATETIME($date, dateStyle: "short", timeStyle: "short") }
full-time = { DATETIME($date, dateStyle: "long", timeStyle: "long") }
