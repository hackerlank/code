#include <stdio.h>
#include "file.h"

int getdigits(int *digits, FILE *fp) 
{
    char line[MAXLENGTH];
    int n = 0;

    while (NULL != fgets(line, MAXLENGTH, fp))
        digits[n++] = atoi(line);
    
    digits[n] = '\0';
    return n;
}

void readdigits(int *p)
{
    int i;
    
    for (i = 0; '\0' != p[i]; i++)
        printf("%d:%d\n", i, p[i]);

}

void savedigits(int *digits, FILE *fp)
{
    int i, j, k;
    char word[MAXLENGTH], line[MAXLENGTH]; 
    
    k = 0;
    for (i = 0; '\0' != digits[i]; i++) {

        snprintf(word, 10, "%d", digits[i]);
        for (j = 0; '\0' != word[j]; j++)
            line[k++] = word[j];

        line[k++] = '\n';
    }
    line[k] = '\0';
    
    fputs(line, fp);
}
