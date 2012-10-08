#include <stdio.h>
#include "file.h"

void shellsort(int *digits, int n);
void main(int argc, char *argv[])
{
    FILE *fp_infile = fopen(argv[1], "r"), *fp_outfile = fopen(argv[2], "w");
    int digits[MAXLENGTH], len;
    
    len = getdigits(digits, fp_infile);
    readdigits(digits);
    shellsort(digits, len);
    readdigits(digits);
    savedigits(digits, fp_outfile);
    
}

void shellsort(int *digits, int n)
{
    int gap, i, j, temp;

    for (gap = n/2; gap > 0; gap /= 2) 
        for (i = gap; i < n; i++) 
            for (j = i - gap; j >= 0 && digits[j] > digits[j+gap]; j -= gap) {
                temp = digits[j];
                digits[j] = digits[j+gap];
                digits[j+gap] = temp;
            }
}
