
# Deploying a new project
Every new project can be deployed to aws lambda without a lot of configuration.

## Default Urls
- Staging: https://{project-name}-staging.projects.021-factory.com
- Production: https://{project-name}.projects.021-factory.com
## Custom Domains
[] Register Domain (CloudFlare recommended)
[] Register [ACM Certificate in AWS](https://eu-central-1.console.aws.amazon.com/acm/home?region=us-east-1#/certificates/list): Make sure to copy the cname name and value from aws to cloudflare (proxy status off)
    - first create a new certificate in us-east-1
    - then create a new certificate in eu-central-1
[] Create a hosted zone in Route 53
[] Set hostedZoneId and acm_certificate_arn in project.yml
[] Create an APP_KEY [SSM parameters]() syntax: /[app.id]/app_key
[] Make your first deployment
[] Set cloudflare cname:
    - name: @ or the subdomain
    - value: xyz.cloudfront.net (deployment output)
    - proxy status: on 


### Troubleshooting
ERROR:
API Mappings:
Invalid domain name identifier specified

SOLUTION:
- Check if the domainName is valid and correct

ISSUE:
- Deployment takes longer than usual after updating project.yml

ISSUE:
Stack:arn:aws:cloudformation:eu-central-1:202533508550:stack/lft-staging/df28e240-b7ef-11ef-9605-0a9da4db0c3f is in UPDATE_IN_PROGRESS state and can not be updated.

ISSUE:
New domain too many redirects.

SOLUTION:
- Make sure cloudflare ssl/tls is set to full (not strict)


## Email
We use resend as the prefefered email provider since it is very easy to set up and has a great DX.
By default, you can use any email from the domain 021-fast.fun. 
If the project requires a different domain, we can always create a [new domain](https://resend.com/domains)


