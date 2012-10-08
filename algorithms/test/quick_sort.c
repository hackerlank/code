#include <stdio.h>
#include "file.h"

void swap(int *v, int i, int j);
void quicksort(int *digits, int left, int right);
void main(int argc, char *argv[])
{
    FILE *fp_infile = fopen(argv[1], "r"), *fp_outfile = fopen(argv[2], "w");
    int digits[MAXLENGTH], len;
    
    len = getdigits(digits, fp_infile);
    readdigits(digits);
    quicksort(digits, 0, len);
    readdigits(digits);
    savedigits(digits, fp_outfile);
    
}

void swap(int *v, int i, int j)
{
    int temp;
    temp = v[i];
    v[i] = v[j];
    v[j] = temp;
}
void quicksort(int *digits, int left, int right)
{
    int i, last;
    void swap(int *v, int m, int n);

    if (left >= right)
        return;

    swap(digits, left, (left + right)/2);
    last = left;
    for (i = left+1; i < right; i++)
        if (digits[i] < digits[left]) 
            swap(digits,++last, i);
    swap(digits, left, last);
   
    quicksort(digits, left, last-1);
    quicksort(digits, last+1, right);
}
