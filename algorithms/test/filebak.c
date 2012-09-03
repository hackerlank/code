/*
 *version:1.0
#include <stdio.h>

int main()
{
    int digits[6], sorted[6];
    digits[0] = 31;
    digits[1] = 41;
    digits[2] = 59;
    digits[3] = 26;
    digits[4] = 41;
    digits[5] = 58;

    int i, j;
    
    sorted[0] = digits[0];
    for (j = 1; j < 6; j++) {
        i = j -1;
        while (i > 0 && sorted[i] > digits[j]) {
            sorted[i+1] = sorted[i];
            i = i-1;
        } 
        sorted[i+1] = digits[j];
    }
    
    for (i = 0; i < 6; i++)
        printf("%d\n", sorted[i]);

    return 1;
}
*/
/*
 *version: 2.0
#include <stdio.h>

#define MAXLENGTH 1000

void main()
{
    int c, i, digits[MAXLENGTH], sorted[MAXLENGTH];

    for (i = 0 ; 1 == scanf("%d", &c) && i < MAXLENGTH; i++)
       digits[i] = c;
    digits[i] = '\0';

    
    for (i = 0; '\0' != digits[i] && i < MAXLENGTH; i++)
        printf("%d:%d\n", i, digits[i]); 
    
    sorted[0] = digits[0];
    for (i = 1; '\0' != digits[i] && i < MAXLENGTH; i++){
        c = i -1;
        while (i > 0 && sorted[c] > digits[i]) {
            sorted[c+1] = sorted[c];
            c = c - 1;
        }
        sorted[c+1] = digits[i];
    }

    for (i = 0; '\0' != sorted[i] && i < MAXLENGTH; i++)
        printf("%d:%d\n", i, sorted[i]); 
}
*/
/*
 *version:3.0
#include<stdio.h>

#define MAXLENGTH 1000

void readdigits(int *p);
void sorteddigits(int *origin, int *sorted);

int main()
{
    int origin[MAXLENGTH], sorted[MAXLENGTH];
   
    int c,i;
    for (i =0; 1 == scanf("%d", &c) && i < MAXLENGTH; i++)
        origin[i] = c;
    printf("you input digits is:\n");
    readdigits(origin);
    sorteddigits(origin, sorted);
    printf("sorted digits is:\n");
    readdigits(sorted); 
}

void readdigits(int *p)
{
    int i;
    for (i = 0; '\0' != p[i]; i++)
        printf("%d:%d\n", i, p[i]);
}

void sorteddigits(int *origin, int *sorted)
{
    int i,j;
    sorted[0] = origin[0];
    for (j = 1; '\0' != origin[j]; j++) {
        i = j -1;
        while (i >0 && sorted[i] > origin[j]) {
            sorted[i+1] = sorted[i];
            i = i -1;
        } 
        sorted[i+1] = origin[j];
    }
}
*/
/*
 *vesrion 4.0
#include<stdio.h>

#define MAXLENGTH 1000

void readdigits(int *p);
void sorteddigits(int *digits);

int main()
{
    int digits[MAXLENGTH];
   
    int c,i;
    for (i =0; 1 == scanf("%d", &c) && i < MAXLENGTH; i++)
        digits[i] = c;
    printf("you input digits is:\n");
    readdigits(digits);
    sorteddigits(digits);
    printf("sorted digits is:\n");
    readdigits(digits); 
}

void readdigits(int *p)
{
    int i;
    for (i = 0; '\0' != p[i]; i++)
        printf("%d:%d\n", i, p[i]);
}

void sorteddigits(int *digits)
{
    int i,j,current_digit;
    for (j = 1; '\0' != digits[j]; j++) {
        i = j -1;
        current_digit = digits[j];
        while (i >0 && digits[i] > current_digit) {
            digits[i+1] = digits[i];
            i = i -1;
        } 
        digits[i+1] = current_digit;
    }
}
*/
/*
 *version 5.0
#include<stdio.h>

#define MAXLENGTH 1000

int powEx(int x, int y);

main(int argc, char *argv[])
{
    FILE *fp_in;
    char line[MAXLENGTH], word[MAXLENGTH];
    int i, digits[MAXLENGTH], j, k, m, digit;

    fp_in = fopen(argv[1], "r");
    m = 0;
    while (fgets(line, MAXLENGTH, fp_in) != NULL) {
        j = 0;
        for(i = 0; '\0' != line[i]; i++) {
            if ((int)line[i] >= 48 && (int)line[i] <= 57) {
                word[j++] = line[i];
            }
        }
        
        digit = 0;
        for (k = 0; k < j; k++) {
            int y = j-k-1;
            int base = powEx(10,y);
            digit += base*((int)word[k]-48); 
        }
        digits[m++] = digit;
    }
    digits[m++] = '\0';
    for (i = 0; '\0' != digits[i]; i++)
        printf("%d\n", digits[i]);
    
}

int powEx(int x, int y)
{
    if (y <= 0)
        return 1;

    int i;
    for (i = 1; i < y; i++)
        x *= x;
    return x;
}
*/
/*
 *version:6.0
#include<stdio.h>

#define MAXLENGTH 1000

int powEx(int x, int y);
void getdigits(int digits[], FILE *fp);

main(int argc, char *argv[])
{
    FILE *fp_in;
    char line[MAXLENGTH], word[MAXLENGTH];
    int i, digits[MAXLENGTH];

    fp_in = fopen(argv[1], "r");
    getdigits(digits, fp_in);

    for (i = 0; '\0' != digits[i]; i++)
        printf("%d\n", digits[i]);
    
}

int powEx(int x, int y)
{
    if (y <= 0)
        return 1;

    int i;
    for (i = 1; i < y; i++)
        x *= x;
    return x;
}
void getdigits(int digits[], FILE *fp)
{
    char line[MAXLENGTH], word[MAXLENGTH];
    int i,j,k,m,n,base,digit;
    n = 0;
    while(fgets(line, MAXLENGTH, fp) != NULL){
        j = 0;
        for(i = 0; '\0' != line[i]; i++) 
            if ((int)line[i] >= 48 && (int)line[i] <= 57)
                word[j++] = line[i];
        digit = 0;
        for(k = 0; k < j;k++) {
            m = j-k-1;
            base = powEx(10,m);
            digit += base*((int)word[k]-48);
        }
        digits[n++] = digit;
    }
    digits[n++] = '\0';
}
*/
/*
 * version 7.0
#include<stdio.h>

#define MAXLENGTH 1000

int powEx(int x, int y);
void getdigits(int digits[], FILE *fp);
void readdigits(int *p);
void sorteddigits(int *digits);
main(int argc, char *argv[])
{
    FILE *fp_in;
    char line[MAXLENGTH], word[MAXLENGTH];
    int i, digits[MAXLENGTH];

    fp_in = fopen(argv[1], "r");
    getdigits(digits, fp_in);

    readdigits(digits);
    sorteddigits(digits);
    readdigits(digits);
 
}

int powEx(int x, int y)
{
    if (y <= 0)
        return 1;

    int i;
    for (i = 1; i < y; i++)
        x *= x;
    return x;
}
void getdigits(int digits[], FILE *fp)
{
    char line[MAXLENGTH], word[MAXLENGTH];
    int i,j,k,m,n,base,digit;
    n = 0;
    while(fgets(line, MAXLENGTH, fp) != NULL){
        j = 0;
        for(i = 0; '\0' != line[i]; i++) 
            if ((int)line[i] >= 48 && (int)line[i] <= 57)
                word[j++] = line[i];
        digit = 0;
        for(k = 0; k < j;k++) {
            m = j-k-1;
            base = powEx(10,m);
            digit += base*((int)word[k]-48);
        }
        digits[n++] = digit;
    }
    digits[n++] = '\0';
}
void readdigits(int *p)
{
    int i;
    for (i = 0; '\0' != p[i]; i++)
        printf("%d:%d\n", i, p[i]);
}

void sorteddigits(int *digits)
{
    int i,j,current_digit;
    for (j = 1; '\0' != digits[j]; j++) {
        i = j -1;
        current_digit = digits[j];
        while (i >0 && digits[i] > current_digit) {
            digits[i+1] = digits[i];
            i = i -1;
        } 
        digits[i+1] = current_digit;
    }
}
*/
#include<stdio.h>

#define MAXLENGTH 1000

void getdigits(int *digits, FILE *fp);
void readdigits(int *p);
void sorteddigits(int *digits);
void saveddigits(int *digits, FILE *fp);
main(int argc, char *argv[])
{
    FILE *fp_in, *fp_out;
    char line[MAXLENGTH], word[MAXLENGTH];
    int i, digits[MAXLENGTH];

    fp_in = fopen(argv[1], "r");
    fp_out = fopen(argv[2], "w");
    getdigits(digits, fp_in);

    readdigits(digits);
    sorteddigits(digits);
    readdigits(digits);
    saveddigits(digits, fp_out);
}

void getdigits(int *digits, FILE *fp)
{
    char line[MAXLENGTH], word[MAXLENGTH];
    int n = 0;
    while (fgets(line, MAXLENGTH, fp) != NULL) 
        digits[n++] = atoi(line);
    digits[n++] = '\0';
}
void readdigits(int *p)
{
    int i;
    for (i = 0; '\0' != p[i]; i++)
        printf("%d:%d\n", i, p[i]);
}

void sorteddigits(int *digits)
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
void saveddigits(int *digits, FILE *fp)
{
    int i,j,k;
    char word[MAXLENGTH], line[MAXLENGTH];
    k = 0;
    for (i = 0; '\0' != digits[i]; i++) {
        snprintf(word, 10, "%d", digits[i]);
        for (j = 0; '\0' != word[j]; j++)
            line[k++] = word[j];
        line[k++] = '\n';
    }
    fputs(line, fp);
}
