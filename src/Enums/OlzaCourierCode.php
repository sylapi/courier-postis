<?php

declare(strict_types=1);

namespace Sylapi\Courier\Postis\Enums;

enum OlzaCourierCode: string {
    case GLS = 'GLS';
    case GLS_PS = 'GLS-PS';
    case CP = 'CP';
    case CP_RR = 'CP-RR';
    case CP_NP = 'CP-NP';
    case CP_BAL = 'CP-BAL';
    case SP = 'SP';
    case DPD = 'DPD';
    case PPL_PAR = 'PPL-PAR';
    case PPL_PS = 'PPL-PS';
    case PPL_RET = 'PPL-RET';
    case ZAS = 'ZAS';
    case ZAS_P = 'ZAS-P';
    case ZAS_K = 'ZAS-K';
    case ZAS_D = 'ZAS-D';
    case ZAS_C = 'ZAS-C';
    case ZAS_B = 'ZAS-B';
    case ZAS_COL = 'ZAS-COL';
    case GEIS_P = 'GEIS-P';
    case BMCG_IPK = 'BMCG-IPK';
    case BMCG_IPKP = 'BMCG-IPKP';
    case BMCG_DHL = 'BMCG-DHL';
    case BMCG_PPK = 'BMCG-PPK';
    case BMCG_PPE = 'BMCG-PPE';
    case BMCG_UC = 'BMCG-UC';
    case BMCG_HUP = 'BMCG-HUP';
    case BMCG_FAN = 'BMCG-FAN';
    case BMCG_INT = 'BMCG-INT';
    case BMCG_INT_PP = 'BMCG-INT-PP';
    case ZAS_ECONT_HD = 'ZAS-ECONT-HD';
    case ZAS_ECONT_PP = 'ZAS-ECONT-PP';
    case ZAS_ECONT_BOX = 'ZAS-ECONT-BOX';
    case ZAS_ACS_HD = 'ZAS-ACS-HD';
    case ZAS_ACS_PP = 'ZAS-ACS-PP';
    case ZAS_SPEEDY_PP = 'ZAS-SPEEDY-PP';
    case ZAS_SPEEDY_HD = 'ZAS-SPEEDY-HD';
}
