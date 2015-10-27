<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CF\gavoters;

/**
 * Description of BuildLatestVoters
 *
 * @author james
 */
class BuildLatestVoters {
    //put your code here
    public function getNativeSQL() {
        return <<<EOT
DROP TABLE if exists georgia_matched_voters;
CREATE TABLE georgia_matched_voters
SELECT vtrs.* FROM (
	SELECT v2.RegistrationNbr,MAX(v2.ExportDate) as ExportDate FROM (
		SELECT v.* FROM georgia_matches g
		JOIN Voters AS v ON g.ga_voter_id_2 = v.RegistrationNbr
	) AS v2 GROUP BY v2.RegistrationNbr
) AS v3
JOIN Voters AS vtrs ON v3.RegistrationNbr = vtrs.RegistrationNbr AND v3.ExportDate = vtrs.ExportDate        
EOT;
    }
}
