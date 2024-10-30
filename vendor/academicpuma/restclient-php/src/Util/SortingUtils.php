<?php

namespace AcademicPuma\RestClient\Util;

use AcademicPuma\RestClient\Model\Bibtex;

class SortingUtils
{
    /**
     * @param Bibtex $a
     * @param Bibtex $b
     * @return int
     */
    public static function compareMonth(Bibtex $a, Bibtex $b): int
    {
        $a_month = BibtexUtils::getMonthCleaned($a);
        $b_month = BibtexUtils::getMonthCleaned($b);

        return $a_month > $b_month ? 1 : -1;
    }

    /**
     * Comparator for the publication in the yearly grouped publication
     * for the DBLP-like sorting
     * @param Bibtex $a
     * @param Bibtex $b
     *
     * @return int
     */
    public static function compareDBLPFormat(Bibtex $a, Bibtex $b): int
    {
        $journalA = $a->getJournal();
        $journalB = $b->getJournal();

        /*
         * Informal publication category from DBLP are at the bottom,
         * categorizing as informal, they are listed under certain journals.
         * Can't check after arxiv entry, since there is no norm for it.
         * For more info: http://dblp.org/faq/What+types+does+dblp+use+for+publication+entries
         */
        if (BibtexUtils::isInformalPublication($journalA) || BibtexUtils::isInformalPublication($journalB)) {
            if (BibtexUtils::isInformalPublication($journalA) && !BibtexUtils::isInformalPublication($journalB)) {
                return 1;
            } else if (!BibtexUtils::isInformalPublication($journalA) && BibtexUtils::isInformalPublication($journalB)) {
                return -1;
            } else if (BibtexUtils::isInformalPublication($journalA) && BibtexUtils::isInformalPublication($journalB)) {
                return strcasecmp($journalA, $journalB);
            }
        }

        // Primary: Year, but function is used for groups with publication with the same year
        // Secondary: Compare entrytype
        $entryCmp = strcasecmp($a->getEntrytype(), $b->getEntrytype());
        if ($entryCmp === 0) {
            // Tertiary: Compare journal/conference, if both have same entrytype
            if ($a->getEntrytype() == 'inproceedings') {
                // Entrytype: Proceedings
                $conference = $a->getConference();
                $bConference = $b->getConference();
                $journalConferenceCmp = strcasecmp($conference, $bConference);
            } else {
                $journalConferenceCmp = strcasecmp($a->getJournal(), $b->getJournal());
            }
            if ($journalConferenceCmp === 0) {
                // Quaternary: Compare pages, if both are in the same journal/conference
                return BibtexUtils::getStartingPage($a->getPages()) > BibtexUtils::getStartingPage($b->getPages()) ? 1 : -1;
            } else {
                return $journalConferenceCmp;
            }
        } else {
            return $entryCmp;
        }
    }
}