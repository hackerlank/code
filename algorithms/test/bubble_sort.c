#include <stdio.h>
#include "file.h"

void bubblesort(int *digits);
void main(int argc, char *argv[])
{
    FILE *fp_infile = fopen(argv[1], "r"), *fp_outfile = fopen(argv[2], "w");
    int digits[MAXLENGTH];
    
    getdigits(digits, fp_infile);
    readdigits(digits);
    bubblesort(digits);
    readdigits(digits);
    savedigits(digits, fp_outfile);
    
}

void bubblesort(int *digits)
{
    int i,j,current_digit;

    for (i = 0; '\0' != digits[i]; i++)
        for (j=i+1; '\0' != digits[j]; j++) 
            if (digits[i] > digits[j]) {
                current_digit = digits[i];
                digits[i] = digits[j];
                digits[j] = current_digit;
            }
}
