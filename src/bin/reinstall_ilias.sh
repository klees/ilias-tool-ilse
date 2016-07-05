#!/bin/bash

php src/bin/delete_ilias.php $1
php src/bin/install.php $1 $2
mail -s "Instanz erfolgreich erneuert" stefan.hecken@concepts-and-training.de