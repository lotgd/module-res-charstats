# Resource module: Charstats
![Tests](https://github.com/lotgd/module-res-charstats/workflows/Tests/badge.svg)

This module adds an event listener to populate CharStats with basic character stats.

It is planned at a later state to expand this module to make it as customizable as 
possible. For now, modification is not possible.

## API

### Character stats

Adds a CharacterStat for progress bars: 
`LotGD\Module\Res\Charstats\CharacterStats\ProgressBarCharacterStat`

#### Options
- `min`, float, default 0; Value at which the progress bar is empty.
- `max`, float, default 100; Value at which the progress bar is full.