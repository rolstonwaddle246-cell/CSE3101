# Student Report Card page
### To work on later:
- right now i'm hardcoding 'subject_grade' and 'remarks' in the report_card_details table, whenever i create a record for this table. That will have to change when the Score page (where teachers enter scores) is built. The score pg is suppose to automatically calculate the grade and remarks based on the grading system (i have a table for grading system too). 
- make the page responsive

### If this Student Report Card page doesn't work for u, it might be bc of ur db. Like e.g. your db table names should match the ones in the query code (in the controller, models and so on)

### Improvements to add: 
- messages on why the report isnt generated when u click generate. It's likely because the db tables arent filled completely with data. Us developers know this but the users might not.
- export formats need some nicen up, like the print pdf (cols doesnt line up properly) and so on
