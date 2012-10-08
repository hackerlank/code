#include <stdio.h>
#include "file.h"

void insertsort(int *digits);
void main(int argc, char *argv[])
{
    FILE *fp_infile = fopen(argv[1], "r"), *fp_outfile = fopen(argv[2], "w");
    int digits[MAXLENGTH];
    
    getdigits(digits, fp_infile);
    readdigits(digits);
    insertsort(digits);
    readdigits(digits);
    savedigits(digits, fp_outfile);
    
}
void insertsort(int *digits)
{
    int i,j,current_digit;
    for (j = 1; '\0' != digits[j]; j++) {
        i = j -1;
        current_digit = digits[j];
        while (i >= 0 && digits[i] > current_digit) {
            digits[i+1] = digits[i];
            i = i -1;
        } 
        digits[i+1] = current_digit;
    }
}
