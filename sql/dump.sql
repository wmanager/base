--
-- postgresQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';

--
-- Name: accounts; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE accounts (
    id integer NOT NULL,
    account_type character varying(50),
    first_name character varying(50),
    last_name character varying(50),
    code character varying(50),
    address_id integer,
    company_id integer,
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer
);


ALTER TABLE public.accounts OWNER TO install_host_username;

--
-- Name: accounts_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE accounts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.accounts_id_seq OWNER TO install_host_username;

--
-- Name: accounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE accounts_id_seq OWNED BY accounts.id;


--
-- Name: activities; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE activities (
    id integer NOT NULL,
    id_thread integer,
    type character varying(255),
    note text,
    created timestamp(6) without time zone,
    created_by integer,
    modified timestamp(6) without time zone,
    modified_by integer,
    owner_company integer,
    creator_company integer,
    id_contract integer,
    url_wizard character varying(255),
    deadline timestamp(6) without time zone,
    duty_operator integer,
    payload text,
    form_id integer,
    closed_date timestamp(6) without time zone,
    status character varying(100) DEFAULT 'OPEN'::character varying,
    status_modified timestamp without time zone,
    status_detail character varying(100),
    description text
);


ALTER TABLE public.activities OWNER TO install_host_username;

--
-- Name: activities_acl; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE activities_acl (
    id integer NOT NULL,
    activities_id integer,
    role_key character varying(255),
    duty_company integer,
    duty_user integer,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.activities_acl OWNER TO install_host_username;

--
-- Name: activities_acl_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE activities_acl_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activities_acl_id_seq OWNER TO install_host_username;

--
-- Name: activities_acl_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE activities_acl_id_seq OWNED BY activities_acl.id;


--
-- Name: activities_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activities_id_seq OWNER TO install_host_username;

--
-- Name: activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE activities_id_seq OWNED BY activities.id;


--
-- Name: address; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE address (
    id integer NOT NULL,
    type character varying(50),
    address character varying(255),
    zip character varying(50),
    city character varying(50),
    state character varying(50),
    country character varying(50),
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer
);


ALTER TABLE public.address OWNER TO install_host_username;

--
-- Name: address_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE address_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.address_id_seq OWNER TO install_host_username;

--
-- Name: address_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE address_id_seq OWNED BY address.id;


--
-- Name: api_keys; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE api_keys (
    id integer NOT NULL,
    id_company integer,
    key character varying(32),
    active boolean,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer,
    domain character varying(255)
);


ALTER TABLE public.api_keys OWNER TO install_host_username;

--
-- Name: api_keys_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE api_keys_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.api_keys_id_seq OWNER TO install_host_username;

--
-- Name: api_keys_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE api_keys_id_seq OWNED BY api_keys.id;


--
-- Name: assets; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE assets (
    id integer NOT NULL,
    be_id integer,
    contract_id integer,
    assets_type character varying(50),
    start_date date,
    end_date date,
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer
);


ALTER TABLE public.assets OWNER TO install_host_username;

--
-- Name: assets_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.assets_id_seq OWNER TO install_host_username;

--
-- Name: assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE assets_id_seq OWNED BY assets.id;


--
-- Name: attachments; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE attachments (
    id integer NOT NULL,
    attach_type integer,
    be_id integer,
    client_id integer,    
    filename character varying(255),
    url character varying(255),
    description text,
    entity_key character varying(255),
    entity_id integer,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone,
    modified_by integer
);


ALTER TABLE public.attachments OWNER TO install_host_username;

--
-- Name: attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.attachments_id_seq OWNER TO install_host_username;

--
-- Name: attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE attachments_id_seq OWNED BY attachments.id;


--
-- Name: be; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE be (
    id integer NOT NULL,
    be_code character varying(50),
    type character varying(50),
    invoice_method character varying(50),
    invoice_payment_method character varying(50),
    invoice_address integer,
    email character varying(255),
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer,
    account_id integer,
    be_status character varying(100)
);


ALTER TABLE public.be OWNER TO install_host_username;

--
-- Name: be_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE be_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.be_id_seq OWNER TO install_host_username;

--
-- Name: be_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE be_id_seq OWNED BY be.id;



--
-- Name: ci_sessions; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE ci_sessions (
    session_id character varying(40) DEFAULT '0'::character varying NOT NULL,
    ip_address character varying(16) DEFAULT '0'::character varying NOT NULL,
    user_agent character varying(150) NOT NULL,
    last_activity integer DEFAULT 0 NOT NULL,
    user_data text NOT NULL,
    username character varying(150),
    created timestamp without time zone DEFAULT now()
);


ALTER TABLE public.ci_sessions OWNER TO install_host_username;

--
-- Name: companies; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE companies (
    id integer NOT NULL,
    owner_yn boolean,
    domain character varying(255),
    name character varying(255),
    vat_code character varying(255),
    billing_address_street character varying(255),
    billing_address_city character varying(255),
    billing_address_province character varying(255),
    billing_address_state character varying(255),
    billing_address_country character varying(255),
    billing_address_zip character varying(255),
    shipping_address_street character varying(255),
    shipping_address_city character varying(255),
    shipping_address_province character varying(255),
    shipping_address_state character varying(255),
    shipping_address_country character varying(255),
    shipping_address_zip character varying(255),
    phone1 character varying(255),
    phone2 character varying(255),
    email1 character varying(255),
    email2 character varying(255),
    fax character varying(255),
    main_contact_name character varying(255),
    created timestamp(6) without time zone DEFAULT now(),
    modified timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified_by integer,
    active boolean DEFAULT true,
    contact character varying(255),
    icon character varying(255),
    holding integer,
    parent_company integer
);


ALTER TABLE public.companies OWNER TO install_host_username;

--
-- Name: companies_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE companies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.companies_id_seq OWNER TO install_host_username;

--
-- Name: companies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE companies_id_seq OWNED BY companies.id;


--
-- Name: contacts; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE contacts (
    id integer NOT NULL,
    contact_type character varying(50),
    account_id integer,
    value character varying(100),
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer
);


ALTER TABLE public.contacts OWNER TO install_host_username;

--
-- Name: contacts_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE contacts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contacts_id_seq OWNER TO install_host_username;

--
-- Name: contacts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE contacts_id_seq OWNED BY contacts.id;


--
-- Name: contracts; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE contracts (
    id integer NOT NULL,
    product_id integer,
    title character varying(50),
    contract_code character varying(50),
    contract_type character varying(50),
    d_sign date,
    validity_start date,
    validity_end date,
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer
);


ALTER TABLE public.contracts OWNER TO install_host_username;


--
-- Name: contracts_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE contracts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contracts_id_seq OWNER TO install_host_username;

--
-- Name: contracts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE contracts_id_seq OWNED BY contracts.id;


--
-- Name: delete_history; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE delete_history (
    tablename character varying(100),
    data text,
    date timestamp without time zone DEFAULT now(),
    query text
);


ALTER TABLE public.delete_history OWNER TO install_host_username;

--
-- Name: dependencies; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE dependencies (
    id integer NOT NULL,
    template character varying(255),
    type character varying(255),
    filename character varying(255),
    path character varying(255),
    module character varying(100),
    "order" integer,
    module_order integer
);


ALTER TABLE public.dependencies OWNER TO install_host_username;

--
-- Name: dependencies_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE dependencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.dependencies_id_seq OWNER TO install_host_username;

--
-- Name: dependencies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE dependencies_id_seq OWNED BY dependencies.id;

--
-- Name: email_queue; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE email_queue (
    id integer NOT NULL,
    "to" character varying(255) NOT NULL,
    cc character varying(255),
    bcc character varying(255),
    message text NOT NULL,
    status character varying(255) NOT NULL,
    date timestamp without time zone NOT NULL,
    headers text,
    be_id numeric,
    account_id numeric,
    activity_id numeric,
    thread_id numeric,
    email_type character varying(255),
    memo_id integer
);


ALTER TABLE public.email_queue OWNER TO install_host_username;

--
-- Name: email_queue_attachments; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE email_queue_attachments (
    id integer NOT NULL,
    email_id integer,
    attachment_id integer
);


ALTER TABLE public.email_queue_attachments OWNER TO install_host_username;

--
-- Name: email_queue_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE email_queue_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.email_queue_attachments_id_seq OWNER TO install_host_username;

--
-- Name: email_queue_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE email_queue_attachments_id_seq OWNED BY email_queue_attachments.id;


--
-- Name: email_queue_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE email_queue_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.email_queue_id_seq OWNER TO install_host_username;

--
-- Name: email_queue_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE email_queue_id_seq OWNED BY email_queue.id;


--
-- Name: email_template; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE email_template (
    id integer NOT NULL,
    email_type character varying(255),
    subject character varying(255),
    template_url character varying(255),
    created timestamp without time zone,
    modified timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.email_template OWNER TO install_host_username;

--
-- Name: email_template_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE email_template_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.email_template_id_seq OWNER TO install_host_username;

--
-- Name: email_template_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE email_template_id_seq OWNED BY email_template.id;


--
-- Name: extension_installer_log; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE extension_installer_log (
    id integer NOT NULL,
    extension_key character varying(100),
    instruction character varying(255),
    message character varying,
    status character varying(100)
);


ALTER TABLE public.extension_installer_log OWNER TO install_host_username;

--
-- Name: extension_installer_log_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE extension_installer_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.extension_installer_log_id_seq OWNER TO install_host_username;

--
-- Name: extension_installer_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE extension_installer_log_id_seq OWNED BY extension_installer_log.id;


--
-- Name: extensions; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE extensions (
    id integer NOT NULL,
    status character varying(10),
    created timestamp without time zone,
    module_name character varying(255),
    key character varying(255),
    file_name character varying(255)
);


ALTER TABLE public.extensions OWNER TO install_host_username;

--
-- Name: extensions_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE extensions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.extensions_id_seq OWNER TO install_host_username;

--
-- Name: extensions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE extensions_id_seq OWNED BY extensions.id;


--
-- Name: form_types; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE form_types (
    id integer NOT NULL,
    label character varying(255),
    file character varying(255),
    ordering integer,
    active boolean
);


ALTER TABLE public.form_types OWNER TO install_host_username;

--
-- Name: form_types_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE form_types_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.form_types_id_seq OWNER TO install_host_username;

--
-- Name: form_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE form_types_id_seq OWNED BY form_types.id;


--
-- Name: groups; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE groups (
    id integer NOT NULL,
    name character varying(20) NOT NULL,
    description character varying(100) NOT NULL
);


ALTER TABLE public.groups OWNER TO install_host_username;

--
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.groups_id_seq OWNER TO install_host_username;

--
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- Name: history; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE history (
    id integer NOT NULL,
    session character varying(255),
    id_thread integer,
    id_activity integer,
    caller_thread character varying(255),
    caller_activity character varying(255),
    action character varying(255),
    key character varying(255),
    value text,
    duty_company character varying(255),
    duty_user character varying(255),
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    note text,
    exit_scenario integer,
    type character varying(255)
);


ALTER TABLE public.history OWNER TO install_host_username;

--
-- Name: history_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.history_id_seq OWNER TO install_host_username;

--
-- Name: history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE history_id_seq OWNED BY history.id;


--
-- Name: list_activities; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_activities (
    id integer NOT NULL,
    id_process integer,
    type text,
    key character varying(255),
    title character varying(255),
    description text,
    role character varying(255),
    weight integer,
    sla integer,
    url text,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.list_activities OWNER TO install_host_username;

--
-- Name: list_activities_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE list_activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.list_activities_id_seq OWNER TO install_host_username;

--
-- Name: list_activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE list_activities_id_seq OWNED BY list_activities.id;


--
-- Name: list_ambits; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_ambits (
    id integer NOT NULL,
    title character varying(255),
    ordering integer,
    disabled boolean DEFAULT false,
    entity_key character varying(255),
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone,
    modified_by integer
);


ALTER TABLE public.list_ambits OWNER TO install_host_username;

--
-- Name: list_ambits_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE list_ambits_id_seq
    START WITH 2
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.list_ambits_id_seq OWNER TO install_host_username;

--
-- Name: list_ambits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE list_ambits_id_seq OWNED BY list_ambits.id;


--
-- Name: list_cause_annullamento; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_cause_annullamento (
    id integer NOT NULL,
    key character varying(255) NOT NULL
);


ALTER TABLE public.list_cause_annullamento OWNER TO install_host_username;


--
-- Name: list_mps; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_mps (
    id integer NOT NULL,
    mp character varying(255),
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.list_mps OWNER TO install_host_username;

--
-- Name: setup_mps; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_mps (
    id integer NOT NULL,
    mp character varying(255),
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_mps OWNER TO install_host_username;

--
-- Name: list_mps_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE list_mps_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.list_mps_id_seq OWNER TO install_host_username;

--
-- Name: list_mps_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE list_mps_id_seq OWNED BY setup_mps.id;

--
-- Name: list_processes; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_processes (
    id integer NOT NULL,
    id_mp integer,
    bpm character varying(255),
    key character varying(255),
    title character varying(255),
    description text,
    role_can_create character varying(255),
    weight integer,
    sla integer,
    url text,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.list_processes OWNER TO install_host_username;

--
-- Name: list_processes_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE list_processes_id_seq
    START WITH 2
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.list_processes_id_seq OWNER TO install_host_username;

--
-- Name: list_processes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE list_processes_id_seq OWNED BY list_processes.id;


--
-- Name: list_vars; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_vars (
    id integer NOT NULL,
    id_process integer,
    id_activity integer,
    source character varying(255),
    type character varying(255),
    key character varying(255),
    description text,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.list_vars OWNER TO install_host_username;

--
-- Name: setup_vars; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_vars (
    id integer NOT NULL,
    id_process integer,
    id_activity integer,
    source character varying(255),
    type character varying(255),
    key character varying(255),
    description text,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer,
    var_label character varying(250),
    layout character varying(250),
    ordering integer
);


ALTER TABLE public.setup_vars OWNER TO install_host_username;

--
-- Name: list_vars_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE list_vars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.list_vars_id_seq OWNER TO install_host_username;

--
-- Name: list_vars_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE list_vars_id_seq OWNED BY setup_vars.id;


--
-- Name: list_vars_values; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE list_vars_values (
    id integer NOT NULL,
    id_var integer,
    key character varying(255),
    description text,
    ordering integer,
    initial boolean DEFAULT false,
    final boolean DEFAULT false,
    final_default boolean DEFAULT false,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.list_vars_values OWNER TO install_host_username;

--
-- Name: setup_vars_values; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_vars_values (
    id integer NOT NULL,
    id_var integer,
    key character varying(255),
    description text,
    ordering integer,
    initial boolean DEFAULT false,
    final boolean DEFAULT false,
    final_default boolean DEFAULT false,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer,
    label character varying(255)
);


ALTER TABLE public.setup_vars_values OWNER TO install_host_username;

--
-- Name: list_vars_values_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE list_vars_values_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.list_vars_values_id_seq OWNER TO install_host_username;

--
-- Name: list_vars_values_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE list_vars_values_id_seq OWNED BY setup_vars_values.id;


--
-- Name: login_attempts; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE login_attempts (
    id integer NOT NULL,
    ip_address inet NOT NULL,
    login character varying(100) NOT NULL,
    "time" integer,
    CONSTRAINT check_id CHECK ((id >= 0))
);


ALTER TABLE public.login_attempts OWNER TO install_host_username;

--
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE login_attempts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.login_attempts_id_seq OWNER TO install_host_username;

--
-- Name: login_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE login_attempts_id_seq OWNED BY login_attempts.id;


--
-- Name: memos; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE memos (
    id integer NOT NULL,
    thread_id integer,
    activity_id integer,
    type character varying(255),
    title character varying(255),
    description text,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone,
    modified_by integer,
    all_day boolean DEFAULT false,
    start_day date,
    end_day date,
    start_time time without time zone,
    end_time time without time zone,
    first_name character varying(255),
    last_name character varying(255),
    address character varying(255),
    city character varying(255),
    state character varying(255),
    country character varying(255),
    zip character varying(255),
    province character varying(255),
    tel character varying(255),
    cell character varying(255),
    email character varying(255),
    note text,
    company character varying(255),
    trouble_id integer,
    parent_id integer,
    customer_id integer,
    isdone boolean DEFAULT false,
    duty_company integer,
    duty_user integer,
    notification_date date
);


ALTER TABLE public.memos OWNER TO install_host_username;

--
-- Name: memos_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE memos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.memos_id_seq OWNER TO install_host_username;

--
-- Name: memos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE memos_id_seq OWNED BY memos.id;



--
-- Name: products; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE products (
    id integer NOT NULL,
    title character varying(50),
    product_code character varying(50),
    product_type character varying(100),
    selling_date date,
    selling_end date,
    created timestamp without time zone,
    created_by integer,
    modified timestamp without time zone,
    modified_by integer
);


ALTER TABLE public.products OWNER TO install_host_username;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE products_id_seq
    START WITH 2
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.products_id_seq OWNER TO install_host_username;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE products_id_seq OWNED BY products.id;


--
-- Name: setup_actions; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_actions (
    id integer NOT NULL,
    name character varying(255),
    description character varying,
    function character varying(255),
    library character varying(255),
    example character varying(255)
);


ALTER TABLE public.setup_actions OWNER TO install_host_username;

--
-- Name: setup_actions_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_actions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_actions_id_seq OWNER TO install_host_username;

--
-- Name: setup_actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_actions_id_seq OWNED BY setup_actions.id;


--
-- Name: setup_activities; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_activities (
    id integer NOT NULL,
    id_process integer,
    type text,
    key character varying(255),
    title character varying(255),
    description text,
    role character varying(255),
    weight integer,
    sla integer,
    disabled boolean DEFAULT false,
    ordering integer,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer,
    form_id integer,
    is_request boolean,
    be_required boolean,
    entry_scenario character varying,
    duty_company numeric,
    help text
);


ALTER TABLE public.setup_activities OWNER TO install_host_username;

--
-- Name: setup_activities_attachments; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_activities_attachments (
    id integer NOT NULL,
    id_activity integer,
    id_attachment integer,
    required boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_activities_attachments OWNER TO install_host_username;

--
-- Name: setup_activities_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_activities_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_activities_attachments_id_seq OWNER TO install_host_username;

--
-- Name: setup_activities_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_activities_attachments_id_seq OWNED BY setup_activities_attachments.id;


--
-- Name: setup_activities_exits; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_activities_exits (
    id integer NOT NULL,
    id_activity integer,
    code integer,
    title character varying(255),
    description text,
    condition text,
    actions text,
    disabled boolean DEFAULT false,
    ordering integer,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_activities_exits OWNER TO install_host_username;

--
-- Name: setup_activities_exits_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_activities_exits_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_activities_exits_id_seq OWNER TO install_host_username;

--
-- Name: setup_activities_exits_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_activities_exits_id_seq OWNED BY setup_activities_exits.id;


--
-- Name: setup_activities_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_activities_id_seq OWNER TO install_host_username;

--
-- Name: setup_activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_activities_id_seq OWNED BY setup_activities.id;


--
-- Name: setup_attachments; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_attachments (
    id integer NOT NULL,
    title character varying(255),
    description text,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer,
    exts character varying(255),
    max_size integer,
    ambit_id integer,
    key character varying(255),
    attach_rename character varying(255)
);


ALTER TABLE public.setup_attachments OWNER TO install_host_username;

--
-- Name: setup_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_attachments_id_seq OWNER TO install_host_username;

--
-- Name: setup_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_attachments_id_seq OWNED BY setup_attachments.id;


--
-- Name: setup_collections_files; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_collections_files (
    id integer NOT NULL,
    id_plico integer,
    id_attachment character varying(255),
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_collections_files OWNER TO install_host_username;

--
-- Name: setup_collections_files_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_collections_files_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_collections_files_id_seq OWNER TO install_host_username;

--
-- Name: setup_collections_files_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_collections_files_id_seq OWNED BY setup_collections_files.id;


--
-- Name: setup_collections_list; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_collections_list (
    id integer NOT NULL,
    title character varying(255),
    description text,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_collections_list OWNER TO install_host_username;

--
-- Name: setup_collections_list_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_collections_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_collections_list_id_seq OWNER TO install_host_username;

--
-- Name: setup_collections_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_collections_list_id_seq OWNED BY setup_collections_list.id;


--
-- Name: setup_company_roles; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_company_roles (
    id integer NOT NULL,
    company_id integer,
    role_key integer,
    operative_yn character varying(255),
    operators_sharing character varying(255),
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_company_roles OWNER TO install_host_username;

--
-- Name: setup_company_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_company_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_company_roles_id_seq OWNER TO install_host_username;

--
-- Name: setup_company_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_company_roles_id_seq OWNED BY setup_company_roles.id;


--
-- Name: setup_config; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_config (
    id integer NOT NULL,
    key character varying(150),
    value character varying(150),
    type character varying(150),
    module character varying(100),
    option character varying(255),
    ab_path boolean DEFAULT false
);


ALTER TABLE public.setup_config OWNER TO install_host_username;

--
-- Name: setup_config_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_config_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_config_id_seq OWNER TO install_host_username;

--
-- Name: setup_config_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_config_id_seq OWNED BY setup_config.id;


--
-- Name: setup_default_vars; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_default_vars (
    id integer NOT NULL,
    domain character varying(255),
    type text,
    key character varying,
    description text,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_default_vars OWNER TO install_host_username;

--
-- Name: setup_default_vars_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_default_vars_id_seq
    START WITH 9
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_default_vars_id_seq OWNER TO install_host_username;

--
-- Name: setup_default_vars_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_default_vars_id_seq OWNED BY setup_default_vars.id;


--
-- Name: setup_forms; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_forms (
    id integer NOT NULL,
    type character varying(255),
    title character varying(255),
    url character varying(255),
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone,
    modified_by integer,
    standard boolean DEFAULT false,
    sidebar boolean DEFAULT false,
    model character varying(255)
);


ALTER TABLE public.setup_forms OWNER TO install_host_username;

--
-- Name: setup_forms_attachments; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_forms_attachments (
    id integer NOT NULL,
    id_attachment integer,
    required boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone,
    modified_by integer,
    form_id integer,
    use character varying(255),
    multi boolean DEFAULT false,
    conditions text
);


ALTER TABLE public.setup_forms_attachments OWNER TO install_host_username;

--
-- Name: setup_forms_attachments_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_forms_attachments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_forms_attachments_id_seq OWNER TO install_host_username;

--
-- Name: setup_forms_attachments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_forms_attachments_id_seq OWNED BY setup_forms_attachments.id;


--
-- Name: setup_forms_collections; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_forms_collections (
    id integer NOT NULL,
    form_id integer,
    id_plico character varying,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_forms_collections OWNER TO install_host_username;

--
-- Name: setup_forms_collections_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_forms_collections_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_forms_collections_id_seq OWNER TO install_host_username;

--
-- Name: setup_forms_collections_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_forms_collections_id_seq OWNED BY setup_forms_collections.id;


--
-- Name: setup_forms_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_forms_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_forms_id_seq OWNER TO install_host_username;

--
-- Name: setup_forms_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_forms_id_seq OWNED BY setup_forms.id;


--
-- Name: setup_master_status; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_master_status (
    id integer NOT NULL,
    key character varying(255),
    label character varying(255),
    ordering integer
);


ALTER TABLE public.setup_master_status OWNER TO install_host_username;

--
-- Name: setup_master_status_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_master_status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_master_status_id_seq OWNER TO install_host_username;

--
-- Name: setup_master_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_master_status_id_seq OWNED BY setup_master_status.id;


--
-- Name: setup_menu; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_menu (
    id integer NOT NULL,
    label character varying,
    link character varying,
    access character varying,
    icon character varying,
    class character varying,
    "order" integer,
    is_child boolean,
    parent_id integer,
    child_order integer,
    module character varying,
    template character varying
);


ALTER TABLE public.setup_menu OWNER TO install_host_username;

--
-- Name: setup_menu_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_menu_id_seq OWNER TO install_host_username;

--
-- Name: setup_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_menu_id_seq OWNED BY setup_menu.id;


--
-- Name: setup_processes; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_processes (
    id integer NOT NULL,
    id_mp integer,
    bpm character varying(255),
    key character varying(255),
    title character varying(255),
    description text,
    role_can_create character varying(255),
    weight integer,
    sla integer,
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer,
    form_id integer,
    wiki_url character varying(255)
);


ALTER TABLE public.setup_processes OWNER TO install_host_username;

--
-- Name: setup_processes_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_processes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_processes_id_seq OWNER TO install_host_username;

--
-- Name: setup_processes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_processes_id_seq OWNED BY setup_processes.id;


--
-- Name: setup_roles; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_roles (
    id integer NOT NULL,
    key character varying(255),
    parent_role character varying(255),
    disabled boolean DEFAULT false,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.setup_roles OWNER TO install_host_username;

--
-- Name: setup_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_roles_id_seq OWNER TO install_host_username;

--
-- Name: setup_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_roles_id_seq OWNED BY setup_roles.id;


--
-- Name: setup_status_transitions; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_status_transitions (
    id integer NOT NULL,
    id_activity_type integer,
    id_process_type integer,
    src_status_key character varying(255),
    dst_status_key character varying(255)
);


ALTER TABLE public.setup_status_transitions OWNER TO install_host_username;

--
-- Name: setup_status_transitions_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_status_transitions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_status_transitions_id_seq OWNER TO install_host_username;

--
-- Name: setup_status_transitions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_status_transitions_id_seq OWNED BY setup_status_transitions.id;

--
-- Name: setup_troubles_status; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_troubles_status (
    id integer NOT NULL,
    key character varying(255),
    label character varying(255),
    ordering integer
);


ALTER TABLE public.setup_troubles_status OWNER TO install_host_username;

--
-- Name: setup_troubles_status_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_troubles_status_id_seq
    START WITH 5
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_troubles_status_id_seq OWNER TO install_host_username;

--
-- Name: setup_troubles_status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_troubles_status_id_seq OWNED BY setup_troubles_status.id;


--
-- Name: setup_troubles_subtypes; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_troubles_subtypes (
    id integer NOT NULL,
    trouble_type integer,
    key character varying(255),
    value character varying(255)
);


ALTER TABLE public.setup_troubles_subtypes OWNER TO install_host_username;

--
-- Name: setup_troubles_subtypes_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_troubles_subtypes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_troubles_subtypes_id_seq OWNER TO install_host_username;

--
-- Name: setup_troubles_subtypes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_troubles_subtypes_id_seq OWNED BY setup_troubles_subtypes.id;


--
-- Name: setup_troubles_types; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_troubles_types (
    id integer NOT NULL,
    title character varying(255),
    description character varying(255),
    active boolean,
    severity integer,
    area character varying(255),
    manual boolean,
    key character varying(255)
);


ALTER TABLE public.setup_troubles_types OWNER TO install_host_username;

--
-- Name: setup_troubles_types_2_processes_types; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_troubles_types_2_processes_types (
    id integer NOT NULL,
    trouble_type integer,
    process_key character varying(255),
    request_key character varying(255),
    autocreate boolean,
    created timestamp without time zone DEFAULT now()
);


ALTER TABLE public.setup_troubles_types_2_processes_types OWNER TO install_host_username;

--
-- Name: setup_troubles_types_2_processes_types_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_troubles_types_2_processes_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_troubles_types_2_processes_types_id_seq OWNER TO install_host_username;

--
-- Name: setup_troubles_types_2_processes_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_troubles_types_2_processes_types_id_seq OWNED BY setup_troubles_types_2_processes_types.id;


--
-- Name: setup_troubles_types_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_troubles_types_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_troubles_types_id_seq OWNER TO install_host_username;

--
-- Name: setup_troubles_types_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_troubles_types_id_seq OWNED BY setup_troubles_types.id;


--
-- Name: setup_users_roles; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE setup_users_roles (
    id integer NOT NULL,
    user_id integer NOT NULL,
    role_id integer NOT NULL
);


ALTER TABLE public.setup_users_roles OWNER TO install_host_username;

--
-- Name: setup_users_roles_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_users_roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_users_roles_id_seq OWNER TO install_host_username;

--
-- Name: setup_users_roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_users_roles_id_seq OWNED BY setup_users_roles.id;


--
-- Name: setup_vars_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE setup_vars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_vars_id_seq OWNER TO install_host_username;

--
-- Name: setup_vars_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE setup_vars_id_seq OWNED BY setup_vars.id;


--
-- Name: threads; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE threads (
    id integer NOT NULL,
    customer integer,
    be integer,
    process character varying(255),
    type character varying(255),
    title character varying(255),
    description text,
    hard_deadline timestamp(6) without time zone,
    deadline timestamp(6) without time zone,
    closed_date timestamp(6) without time zone,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) with time zone,
    modified_by integer,
    url_manage character varying(255),
    progress integer,
    owner_company integer,
    creator_company integer,
    duty_company integer,
    duty_user integer,
    status character varying(100) DEFAULT 'OPEN'::character varying,
    status_modified timestamp without time zone,
    status_detail character varying(100),
    comunica_chiusura_ts timestamp(6) without time zone,
    comunica_chiusura_note text,
    reclamo boolean,
    reclamo_metodo_key character varying(100),
    reclamo_data date,
    reclamo_note character varying(1000),
    draft boolean DEFAULT true,
    pending_parent_thread integer,
    trouble_id integer
);


ALTER TABLE public.threads OWNER TO install_host_username;

--
-- Name: threads_acl; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE threads_acl (
    id integer NOT NULL,
    threads_id integer,
    role_key character varying(255),
    duty_company integer,
    duty_user integer,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.threads_acl OWNER TO install_host_username;

--
-- Name: threads_acl_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE threads_acl_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.threads_acl_id_seq OWNER TO install_host_username;

--
-- Name: threads_acl_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE threads_acl_id_seq OWNED BY threads_acl.id;


--
-- Name: threads_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE threads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.threads_id_seq OWNER TO install_host_username;

--
-- Name: threads_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE threads_id_seq OWNED BY threads.id;


--
-- Name: troubles; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE troubles (
    id integer NOT NULL,
    type_id integer NOT NULL,
    description text,
    deadline date,
    status character varying(255) NOT NULL,
    result character varying(255),
    customer_id integer NOT NULL,
    be_id integer NOT NULL,
    created timestamp without time zone DEFAULT now() NOT NULL,
    created_by integer NOT NULL,
    modified timestamp without time zone,
    modified_by integer,
    res_duty_company integer,
    res_duty_user integer,
    res_role character varying(255),
    subtype character varying(255),
    contratti integer
);


ALTER TABLE public.troubles OWNER TO install_host_username;

--
-- Name: troubles_acl; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE troubles_acl (
    id integer NOT NULL,
    troubles_id integer,
    role_key character varying(255),
    duty_company integer,
    duty_user integer,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.troubles_acl OWNER TO install_host_username;

--
-- Name: troubles_acl_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE troubles_acl_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.troubles_acl_id_seq OWNER TO install_host_username;

--
-- Name: troubles_acl_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE troubles_acl_id_seq OWNED BY troubles_acl.id;


--
-- Name: troubles_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE troubles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.troubles_id_seq OWNER TO install_host_username;

--
-- Name: troubles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE troubles_id_seq OWNED BY troubles.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    ip_address inet NOT NULL,
    username character varying(100) NOT NULL,
    password character varying(80) NOT NULL,
    salt character varying(40),
    email character varying(100) NOT NULL,
    activation_code character varying(40),
    forgotten_password_code character varying(40),
    forgotten_password_time integer,
    remember_code character varying(40),
    created_on integer NOT NULL,
    last_login integer,
    active integer,
    first_name character varying(50),
    last_name character varying(50),
    company character varying(100),
    phone character varying(20),
    id_company integer,
    mobile character varying(20),
    notes text,
    role1 character varying(20),
    master_yn boolean,
    created timestamp(6) without time zone DEFAULT now(),
    created_by character varying(255),
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by character varying(255),
    icon character varying(255),
    domain character varying(255),
    CONSTRAINT check_active CHECK ((active >= 0)),
    CONSTRAINT check_id CHECK ((id >= 0))
);


ALTER TABLE public.users OWNER TO install_host_username;

--
-- Name: users_groups; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE users_groups (
    id integer NOT NULL,
    user_id integer NOT NULL,
    group_id integer NOT NULL
);


ALTER TABLE public.users_groups OWNER TO install_host_username;

--
-- Name: users_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE users_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_groups_id_seq OWNER TO install_host_username;

--
-- Name: users_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE users_groups_id_seq OWNED BY users_groups.id;


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO install_host_username;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- Name: vars; Type: TABLE; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE TABLE vars (
    id integer NOT NULL,
    id_thread integer,
    id_activity integer,
    key character varying(255),
    value text,
    created timestamp(6) without time zone DEFAULT now(),
    created_by integer,
    modified timestamp(6) without time zone DEFAULT now(),
    modified_by integer
);


ALTER TABLE public.vars OWNER TO install_host_username;

--
-- Name: vars_id_seq; Type: SEQUENCE; Schema: public; Owner: install_host_username
--

CREATE SEQUENCE vars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.vars_id_seq OWNER TO install_host_username;

--
-- Name: vars_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: install_host_username
--

ALTER SEQUENCE vars_id_seq OWNED BY vars.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY accounts ALTER COLUMN id SET DEFAULT nextval('accounts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY activities ALTER COLUMN id SET DEFAULT nextval('activities_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY activities_acl ALTER COLUMN id SET DEFAULT nextval('activities_acl_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY address ALTER COLUMN id SET DEFAULT nextval('address_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY assets ALTER COLUMN id SET DEFAULT nextval('assets_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY attachments ALTER COLUMN id SET DEFAULT nextval('attachments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY be ALTER COLUMN id SET DEFAULT nextval('be_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY companies ALTER COLUMN id SET DEFAULT nextval('companies_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY contacts ALTER COLUMN id SET DEFAULT nextval('contacts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY contracts ALTER COLUMN id SET DEFAULT nextval('contracts_id_seq'::regclass);



--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY dependencies ALTER COLUMN id SET DEFAULT nextval('dependencies_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY email_queue ALTER COLUMN id SET DEFAULT nextval('email_queue_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY email_queue_attachments ALTER COLUMN id SET DEFAULT nextval('email_queue_attachments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY email_template ALTER COLUMN id SET DEFAULT nextval('email_template_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY extension_installer_log ALTER COLUMN id SET DEFAULT nextval('extension_installer_log_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY extensions ALTER COLUMN id SET DEFAULT nextval('extensions_id_seq'::regclass);

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY form_types ALTER COLUMN id SET DEFAULT nextval('form_types_id_seq'::regclass);

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY history ALTER COLUMN id SET DEFAULT nextval('history_id_seq'::regclass);

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY list_activities ALTER COLUMN id SET DEFAULT nextval('list_activities_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY list_ambits ALTER COLUMN id SET DEFAULT nextval('list_ambits_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY list_processes ALTER COLUMN id SET DEFAULT nextval('list_processes_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY login_attempts ALTER COLUMN id SET DEFAULT nextval('login_attempts_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY memos ALTER COLUMN id SET DEFAULT nextval('memos_id_seq'::regclass);





--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY products ALTER COLUMN id SET DEFAULT nextval('products_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_actions ALTER COLUMN id SET DEFAULT nextval('setup_actions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_activities ALTER COLUMN id SET DEFAULT nextval('setup_activities_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_activities_attachments ALTER COLUMN id SET DEFAULT nextval('setup_activities_attachments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_activities_exits ALTER COLUMN id SET DEFAULT nextval('setup_activities_exits_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_attachments ALTER COLUMN id SET DEFAULT nextval('setup_attachments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_collections_files ALTER COLUMN id SET DEFAULT nextval('setup_collections_files_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_collections_list ALTER COLUMN id SET DEFAULT nextval('setup_collections_list_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_company_roles ALTER COLUMN id SET DEFAULT nextval('setup_company_roles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_config ALTER COLUMN id SET DEFAULT nextval('setup_config_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_default_vars ALTER COLUMN id SET DEFAULT nextval('setup_default_vars_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_forms ALTER COLUMN id SET DEFAULT nextval('setup_forms_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_forms_attachments ALTER COLUMN id SET DEFAULT nextval('setup_forms_attachments_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_forms_collections ALTER COLUMN id SET DEFAULT nextval('setup_forms_collections_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_master_status ALTER COLUMN id SET DEFAULT nextval('setup_master_status_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_menu ALTER COLUMN id SET DEFAULT nextval('setup_menu_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_mps ALTER COLUMN id SET DEFAULT nextval('list_mps_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_processes ALTER COLUMN id SET DEFAULT nextval('setup_processes_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_roles ALTER COLUMN id SET DEFAULT nextval('setup_roles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_status_transitions ALTER COLUMN id SET DEFAULT nextval('setup_status_transitions_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_troubles_status ALTER COLUMN id SET DEFAULT nextval('setup_troubles_status_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_troubles_subtypes ALTER COLUMN id SET DEFAULT nextval('setup_troubles_subtypes_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_troubles_types ALTER COLUMN id SET DEFAULT nextval('setup_troubles_types_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_troubles_types_2_processes_types ALTER COLUMN id SET DEFAULT nextval('setup_troubles_types_2_processes_types_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_users_roles ALTER COLUMN id SET DEFAULT nextval('setup_users_roles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_vars ALTER COLUMN id SET DEFAULT nextval('list_vars_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY setup_vars_values ALTER COLUMN id SET DEFAULT nextval('list_vars_values_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY threads ALTER COLUMN id SET DEFAULT nextval('threads_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY threads_acl ALTER COLUMN id SET DEFAULT nextval('threads_acl_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY troubles ALTER COLUMN id SET DEFAULT nextval('troubles_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY troubles_acl ALTER COLUMN id SET DEFAULT nextval('troubles_acl_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY users_groups ALTER COLUMN id SET DEFAULT nextval('users_groups_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: install_host_username
--

ALTER TABLE ONLY vars ALTER COLUMN id SET DEFAULT nextval('vars_id_seq'::regclass);


--
-- Name: accounts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('accounts_id_seq', 1, true);


--
-- Name: activities_acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('activities_acl_id_seq', 1, true);


--
-- Name: activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('activities_id_seq', 1, true);


--
-- Name: address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('address_id_seq', 1, false);


--
-- Data for Name: api_keys; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: assets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('assets_id_seq', 1, false);



--
-- Name: attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('attachments_id_seq', 1, true);


--
-- Name: be_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('be_id_seq', 1, true);



--
-- Name: companies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('companies_id_seq', 1, true);



--
-- Name: contacts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('contacts_id_seq', 1, false);



--
-- Name: contracts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('contracts_id_seq', 1, false);


--
-- Data for Name: dependencies; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO dependencies VALUES (1, 'admin', 'js', 'extention.js', '/application/modules/core/assets/js/extention.js', 'core', 1, 1);
INSERT INTO dependencies VALUES (2, 'admin', 'js', 'underscore.js', '/application/modules/core/assets/js/underscore.js', 'core', 2, 1);
INSERT INTO dependencies VALUES (3, 'admin', 'css', 'extention.css', '/application/modules/core/assets/css/extention.css', 'core', 1, 1);


--
-- Name: dependencies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('dependencies_id_seq', 3, true);

--
-- Name: email_queue_attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('email_queue_attachments_id_seq', 1, true);


--
-- Name: email_queue_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('email_queue_id_seq', 1, true);


--
-- Data for Name: email_template; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO email_template VALUES (1, 'FORGOT_PASSWORD', 'FORGOT PASSWORD', '/email_template/forgot_password', NULL, '2017-12-19 12:49:41.3677+05:30');


--
-- Name: email_template_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('email_template_id_seq', 1, true);


--
-- Name: extensions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('extensions_id_seq', 1, true);



--
-- Data for Name: form_types; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: form_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('form_types_id_seq', 1, false);


--
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('groups_id_seq', 2, true);



--
-- Name: history_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('history_id_seq', 1, true);



--
-- Name: list_activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('list_activities_id_seq', 1, false);


--
-- Data for Name: list_ambits; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO list_ambits VALUES (1, 'Connection', 1, false, '2017-12-06 11:33:57.615025', NULL, NULL, NULL);


--
-- Name: list_ambits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('list_ambits_id_seq', 2, true);


--
-- Data for Name: list_cause_annullamento; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO list_cause_annullamento VALUES (1, 'Not proper');


--
-- Name: list_processes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('list_processes_id_seq', 1, false);


--
-- Data for Name: list_vars; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



SELECT pg_catalog.setval('list_vars_id_seq', 1, true);



--
-- Name: list_vars_values_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('list_vars_values_id_seq', 1, true);



--
-- Name: login_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('login_attempts_id_seq', 1, false);




SELECT pg_catalog.setval('memos_id_seq', 1, true);


--
-- Name: products_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('products_id_seq', 1, false);



--
-- Name: setup_actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_actions_id_seq', 1, false);


--
-- Data for Name: setup_activities; Type: TABLE DATA; Schema: public; Owner: install_host_username
--


--
-- Data for Name: setup_activities_attachments; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: setup_activities_attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_activities_attachments_id_seq', 1, false);


--
-- Name: setup_activities_exits_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_activities_exits_id_seq', 1, true);


--
-- Name: setup_activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_activities_id_seq', 1, true);


--
-- Data for Name: setup_attachments; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_attachments VALUES (1, 'attachment unknown', NULL, false, '2017-12-06 11:33:07.0204', NULL, '2017-12-06 11:33:07.0204', NULL, 'pdf,doc,jpg,jpeg,png', 5000, 1, NULL, NULL);


--
-- Name: setup_attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_attachments_id_seq', 1, true);


--
-- Data for Name: setup_collections_files; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: setup_collections_files_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_collections_files_id_seq', 1, false);


--
-- Data for Name: setup_collections_list; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: setup_collections_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_collections_list_id_seq', 1, false);


--
-- Data for Name: setup_company_roles; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: setup_company_roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_company_roles_id_seq', 6, true);


--
-- Data for Name: setup_config; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_config VALUES (2, 'trouble_attach_type', '1', 'text', 'core', NULL, false);
INSERT INTO setup_config VALUES (4, 'email_from_name', 'Wmanager', 'text', 'core', NULL, false);
INSERT INTO setup_config VALUES (3, 'email_from', 'clienti@wmanager.org', 'text', 'email', NULL, false);
INSERT INTO setup_config VALUES (5, 'email_to', 'clienti@wmanager.org', 'text', 'email', NULL, false);
INSERT INTO setup_config VALUES (6, 'email_cc', '', 'text', 'email', NULL, false);
INSERT INTO setup_config VALUES (7, 'loop_check_max_records', '50', 'text', 'core', NULL,false);
INSERT INTO setup_config VALUES (8, 'loop_check_period', '3', 'text', 'core', NULL,false);
INSERT INTO setup_config VALUES (1, 'UPLOAD_DIR', 'assets/uploads/', 'text', 'core', NULL,true);
INSERT INTO setup_config VALUES (9, 'log_path', 'application/logs', 'text', 'core', NULL,true);
INSERT INTO setup_config VALUES (10, 'api_url', 'http://repo.wmanager.org/', 'text', 'core', NULL, false);

--
-- Name: setup_config_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_config_id_seq', 10, true);


--
-- Data for Name: setup_default_vars; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_default_vars VALUES (1, 'PROCESS', 'STATUS', 'STATUS', NULL, false, '2015-05-13 15:36:29.826794', NULL, '2015-05-13 15:36:29.826794', NULL);
INSERT INTO setup_default_vars VALUES (2, 'ACTIVITY', 'STATUS', 'STATUS', NULL, false, '2015-05-13 15:36:44.754783', NULL, '2015-05-13 15:36:44.754783', NULL);
INSERT INTO setup_default_vars VALUES (3, 'ACTIVITY', 'STANDARD', 'RESULT', NULL, false, '2015-05-13 15:37:07.763783', NULL, '2015-05-13 15:37:07.763783', NULL);
INSERT INTO setup_default_vars VALUES (4, 'PROCESS', 'STANDARD', 'RESULT', NULL, false, '2015-05-13 15:37:17.692401', NULL, '2015-05-13 15:37:17.692401', NULL);
INSERT INTO setup_default_vars VALUES (5, 'ACTIVITY', 'STANDARD', 'RESULT_NOTE', NULL, false, '2015-06-24 11:32:22.239097', NULL, '2015-06-24 11:32:22.239097', NULL);
INSERT INTO setup_default_vars VALUES (6, 'PROCESS', 'STANDARD', 'RESULT_NOTE', NULL, false, '2015-06-24 11:33:15.946405', NULL, '2015-06-24 11:33:15.946405', NULL);
INSERT INTO setup_default_vars VALUES (7, 'PROCESS', 'STANDARD', 'RESULT_DATE', NULL, false, '2015-07-13 14:40:50.699473', NULL, '2015-07-13 14:40:50.699473', NULL);
INSERT INTO setup_default_vars VALUES (8, 'ACTIVITY', 'STANDARD', 'RESULT_DATE', NULL, false, '2015-07-13 14:42:07.902848', NULL, '2015-07-13 14:42:07.902848', NULL);


--
-- Name: setup_default_vars_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_default_vars_id_seq', 8, false);


--
-- Data for Name: setup_forms; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_forms VALUES (2, 'ACTIVITY', 'MAGIC_FORM', '/common/cases/activity_detail/magic_form/magic_form', false, '2017-12-05 16:19:27.72142', 1, '2017-12-05 18:07:11', 1, true, false, NULL);
INSERT INTO setup_forms VALUES (1, 'THREAD', 'unknown_thread_form', '/common/cases/activity_detail/plain_form/plain_description', false, '2017-12-05 12:00:34.602342', 1, '2017-12-06 11:24:06', 1, true, false, NULL);


--
-- Data for Name: setup_forms_attachments; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_forms_attachments VALUES (1, 1, false, '2017-12-06 11:35:00.211174', NULL, NULL, NULL, 1, 'UPLOAD', false, NULL);


--
-- Name: setup_forms_attachments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_forms_attachments_id_seq', 1, true);


--
-- Data for Name: setup_forms_collections; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_forms_collections VALUES (1, 2, NULL, '2017-12-05 18:07:11.665206', 1, '2017-12-05 18:07:11.665206', 1);
INSERT INTO setup_forms_collections VALUES (2, 1, NULL, '2017-12-06 11:24:06.644758', 1, '2017-12-06 11:24:06.644758', 1);


--
-- Name: setup_forms_collections_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_forms_collections_id_seq', 2, true);


--
-- Name: setup_forms_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_forms_id_seq', 2, true);


--
-- Data for Name: setup_master_status; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_master_status VALUES (1, 'NEW', 'New', 1);
INSERT INTO setup_master_status VALUES (2, 'WIP', 'In Progress', 2);
INSERT INTO setup_master_status VALUES (3, 'DONE', 'Completed', 3);


--
-- Name: setup_master_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_master_status_id_seq', 3, false);



INSERT INTO products VALUES (1, 'Unknown', 'UNKNOWN', 'UNKNOWN', NULL, NULL, '2018-02-16 15:12:56.655679', NULL, NULL, NULL);

--
-- Data for Name: setup_menu; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_menu VALUES (1, 'Home', NULL, 'admin', 'fa-dashboard', NULL, 1, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (2, 'Clients', '/common/accounts/', 'admin', 'fa-user', NULL, 2, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (3, 'Contracts', '/common/businessentities/', 'admin,operator,controller', 'fa-suitcase', NULL, 3, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (4, 'Troubles', '/common/troubles/', 'admin,operator,controller', 'fa-bug', NULL, 4, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (5, 'Threads', '/common/cases/', 'admin,operator,controller', 'fa-file-text-o', NULL, 5, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (6, 'Activity', '/common/activities/', 'admin,operator,controller', 'fa-tasks', NULL, 6, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (7, 'New', '#', 'admin', 'fa-plus', 'pull-right', 10, false, NULL, NULL, NULL,'wmanager');
INSERT INTO setup_menu VALUES (8, 'New Client', '/common/module_inorder/', 'admin', '', NULL, NULL, true, 7, 1, NULL,'wmanager');

--
-- Name: setup_menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_menu_id_seq', 8, true);


--
-- Data for Name: setup_mps; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_mps VALUES (1, 'UNKNOWN_MP', false, '2017-12-05 11:27:47.358463', NULL, '2017-12-05 11:27:47.358463', NULL);


--
-- Data for Name: setup_processes; Type: TABLE DATA; Schema: public; Owner: install_host_username
--


--
-- Name: setup_processes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_processes_id_seq', 1, true);


--
-- Name: setup_roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_roles_id_seq', 1, true);


--
-- Data for Name: setup_troubles_status; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

INSERT INTO setup_troubles_status VALUES (1, 'NEW', 'New', 1);
INSERT INTO setup_troubles_status VALUES (2, 'WIP', 'In Progress', 2);
INSERT INTO setup_troubles_status VALUES (3, 'DONE', 'Completed', 3);
INSERT INTO setup_troubles_status VALUES (4, 'CANCELLED', 'Cancelled', 4);



--
-- Name: setup_troubles_status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_troubles_status_id_seq', 4, true);


--
-- Data for Name: setup_troubles_subtypes; Type: TABLE DATA; Schema: public; Owner: install_host_username
--


--
-- Name: setup_troubles_subtypes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_troubles_subtypes_id_seq', 1, true);


--
-- Data for Name: setup_troubles_types; Type: TABLE DATA; Schema: public; Owner: install_host_username
--

--
-- Data for Name: setup_troubles_types_2_processes_types; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: setup_troubles_types_2_processes_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_troubles_types_2_processes_types_id_seq', 1, false);


--
-- Name: setup_troubles_types_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_troubles_types_id_seq', 1, true);


--
-- Data for Name: setup_users_roles; Type: TABLE DATA; Schema: public; Owner: install_host_username
--



--
-- Name: setup_users_roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_users_roles_id_seq',  1, true);

--
-- Name: setup_vars_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('setup_vars_id_seq', 1, false);


--
-- Name: threads_acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('threads_acl_id_seq', 1, true);


--
-- Name: threads_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('threads_id_seq', 1, true);



--
-- Name: troubles_acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('troubles_acl_id_seq', 1, false);


--
-- Name: troubles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('troubles_id_seq', 1, true);


--
-- Name: users_groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('users_groups_id_seq', 1, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('users_id_seq', 1, true);

--
-- Name: vars_id_seq; Type: SEQUENCE SET; Schema: public; Owner: install_host_username
--

SELECT pg_catalog.setval('vars_id_seq', 1, true);

--
-- Name: setup_form_type; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE setup_form_type (
    id integer NOT NULL,
    title character varying(100),
    key character varying(100)
);


ALTER TABLE public.setup_form_type OWNER TO install_host_username;

--
-- Name: setup_form_type_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE setup_form_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.setup_form_type_id_seq OWNER TO install_host_username;

--
-- Name: setup_form_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE setup_form_type_id_seq OWNED BY setup_form_type.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY setup_form_type ALTER COLUMN id SET DEFAULT nextval('setup_form_type_id_seq'::regclass);


--
-- Data for Name: setup_form_type; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO setup_form_type VALUES (1, 'ACTIVITY', 'ACTIVITY');
INSERT INTO setup_form_type VALUES (2, 'THREAD', 'THREAD');


--
-- Name: setup_form_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('setup_form_type_id_seq', 2, true);


--
-- Name: setup_form_type_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY setup_form_type
    ADD CONSTRAINT setup_form_type_pkey PRIMARY KEY (id);
    

--
-- Name: accounts_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY accounts
    ADD CONSTRAINT accounts_id PRIMARY KEY (id);


--
-- Name: activities_acl_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY activities_acl
    ADD CONSTRAINT activities_acl_id PRIMARY KEY (id);


--
-- Name: address_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY address
    ADD CONSTRAINT address_id PRIMARY KEY (id);


--
-- Name: assets_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY assets
    ADD CONSTRAINT assets_id PRIMARY KEY (id);


--
-- Name: attachments_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY attachments
    ADD CONSTRAINT attachments_id PRIMARY KEY (id);


--
-- Name: be_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY be
    ADD CONSTRAINT be_id PRIMARY KEY (id);


--
-- Name: ci_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY ci_sessions
    ADD CONSTRAINT ci_sessions_pkey PRIMARY KEY (session_id);


--
-- Name: contacts_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY contacts
    ADD CONSTRAINT contacts_id PRIMARY KEY (id);


--
-- Name: contracts_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY contracts
    ADD CONSTRAINT contracts_id PRIMARY KEY (id);


--
-- Name: email_queue_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY email_queue
    ADD CONSTRAINT email_queue_pkey PRIMARY KEY (id);


--
-- Name: form_types_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY form_types
    ADD CONSTRAINT form_types_pkey PRIMARY KEY (id);


--
-- Name: groups_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_id PRIMARY KEY (id);


--
-- Name: history_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY history
    ADD CONSTRAINT history_id PRIMARY KEY (id);


--
-- Name: list_ambits_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY list_ambits
    ADD CONSTRAINT list_ambits_id PRIMARY KEY (id);



--
-- Name: login_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY login_attempts
    ADD CONSTRAINT login_attempts_pkey PRIMARY KEY (id);


--
-- Name: memos_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY memos
    ADD CONSTRAINT memos_id PRIMARY KEY (id);


--
-- Name: pk_activities; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY activities
    ADD CONSTRAINT pk_activities PRIMARY KEY (id);


--
-- Name: pk_companies; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY companies
    ADD CONSTRAINT pk_companies PRIMARY KEY (id);


--
-- Name: pk_setup_mps_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_mps
    ADD CONSTRAINT pk_setup_mps_id PRIMARY KEY (id);


--
-- Name: pk_threads; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY threads
    ADD CONSTRAINT pk_threads PRIMARY KEY (id);


--
-- Name: products_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY products
    ADD CONSTRAINT products_id PRIMARY KEY (id);


--
-- Name: setup_activities_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_activities_attachments
    ADD CONSTRAINT setup_activities_attachments_pkey PRIMARY KEY (id);


--
-- Name: setup_activities_exits_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_activities_exits
    ADD CONSTRAINT setup_activities_exits_pkey PRIMARY KEY (id);


--
-- Name: setup_activities_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_activities
    ADD CONSTRAINT setup_activities_pkey PRIMARY KEY (id);


--
-- Name: setup_attachments_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_attachments
    ADD CONSTRAINT setup_attachments_pkey PRIMARY KEY (id);


--
-- Name: setup_collections_files_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_collections_files
    ADD CONSTRAINT setup_collections_files_id PRIMARY KEY (id);


--
-- Name: setup_collections_list_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_collections_list
    ADD CONSTRAINT setup_collections_list_id PRIMARY KEY (id);


--
-- Name: setup_company_roles_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_company_roles
    ADD CONSTRAINT setup_company_roles_id PRIMARY KEY (id);


--
-- Name: setup_config_key_key; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_config
    ADD CONSTRAINT setup_config_key_key UNIQUE (key);


--
-- Name: setup_config_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_config
    ADD CONSTRAINT setup_config_pkey PRIMARY KEY (id);


--
-- Name: setup_default_vars_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_default_vars
    ADD CONSTRAINT setup_default_vars_pkey PRIMARY KEY (id);


--
-- Name: setup_forms_attachments_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_forms_attachments
    ADD CONSTRAINT setup_forms_attachments_id PRIMARY KEY (id);


--
-- Name: setup_forms_collections_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_forms_collections
    ADD CONSTRAINT setup_forms_collections_id PRIMARY KEY (id);


--
-- Name: setup_forms_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_forms
    ADD CONSTRAINT setup_forms_id PRIMARY KEY (id);


--
-- Name: setup_master_status_p; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_master_status
    ADD CONSTRAINT setup_master_status_p PRIMARY KEY (id);


--
-- Name: setup_menu_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_menu
    ADD CONSTRAINT setup_menu_pkey PRIMARY KEY (id);


--
-- Name: setup_processes_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_processes
    ADD CONSTRAINT setup_processes_pkey PRIMARY KEY (id);


--
-- Name: setup_rols_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_roles
    ADD CONSTRAINT setup_rols_id PRIMARY KEY (id);


--
-- Name: setup_status_transitions_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_status_transitions
    ADD CONSTRAINT setup_status_transitions_pkey PRIMARY KEY (id);


--
-- Name: setup_troubles_status_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_troubles_status
    ADD CONSTRAINT setup_troubles_status_pkey PRIMARY KEY (id);


--
-- Name: setup_troubles_subtypes_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_troubles_subtypes
    ADD CONSTRAINT setup_troubles_subtypes_pkey PRIMARY KEY (id);


--
-- Name: setup_vars_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_vars
    ADD CONSTRAINT setup_vars_pkey PRIMARY KEY (id);


--
-- Name: setup_vars_values_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY setup_vars_values
    ADD CONSTRAINT setup_vars_values_pkey PRIMARY KEY (id);


--
-- Name: threads_acl_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY threads_acl
    ADD CONSTRAINT threads_acl_id PRIMARY KEY (id);


--
-- Name: troubles_acl_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY troubles_acl
    ADD CONSTRAINT troubles_acl_pkey PRIMARY KEY (id);


--
-- Name: users_groups_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY users_groups
    ADD CONSTRAINT users_groups_id PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: vars_id; Type: CONSTRAINT; Schema: public; Owner: install_host_username; Tablespace: 
--

ALTER TABLE ONLY vars
    ADD CONSTRAINT vars_id PRIMARY KEY (id);


--
-- Name: activities_acl_id_activity_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX activities_acl_id_activity_idx ON activities_acl USING btree (activities_id);


--
-- Name: activities_id_thread_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX activities_id_thread_idx ON activities USING btree (id_thread);


--
-- Name: activities_type_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX activities_type_idx ON activities USING btree (type);



--
-- Name: history_caller_activity_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX history_caller_activity_idx ON history USING btree (caller_activity);


--
-- Name: history_exit_scenario_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX history_exit_scenario_idx ON history USING btree (exit_scenario);


--
-- Name: history_id_activity_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX history_id_activity_idx ON history USING btree (id_activity);



--
-- Name: status_activities_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX status_activities_idx ON activities USING btree (status);


--
-- Name: vars_id_activity_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX vars_id_activity_idx ON vars USING btree (id_activity);


--
-- Name: vars_id_thread_idx; Type: INDEX; Schema: public; Owner: install_host_username; Tablespace: 
--

CREATE INDEX vars_id_thread_idx ON vars USING btree (id_thread);


--
-- Name: public; Type: ACL; Schema: -; Owner: install_host_username
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM install_host_username;
GRANT ALL ON SCHEMA public TO install_host_username;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: activities; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE activities FROM PUBLIC;
REVOKE ALL ON TABLE activities FROM install_host_username;
GRANT ALL ON TABLE activities TO install_host_username;


--
-- Name: activities_acl; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE activities_acl FROM PUBLIC;
REVOKE ALL ON TABLE activities_acl FROM install_host_username;
GRANT ALL ON TABLE activities_acl TO install_host_username;


--
-- Name: attachments; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE attachments FROM PUBLIC;
REVOKE ALL ON TABLE attachments FROM install_host_username;
GRANT ALL ON TABLE attachments TO install_host_username;


--
-- Name: ci_sessions; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE ci_sessions FROM PUBLIC;
REVOKE ALL ON TABLE ci_sessions FROM install_host_username;
GRANT ALL ON TABLE ci_sessions TO install_host_username;


--
-- Name: companies; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE companies FROM PUBLIC;
REVOKE ALL ON TABLE companies FROM install_host_username;
GRANT ALL ON TABLE companies TO install_host_username;


--
-- Name: form_types; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE form_types FROM PUBLIC;
REVOKE ALL ON TABLE form_types FROM install_host_username;
GRANT ALL ON TABLE form_types TO install_host_username;


--
-- Name: groups; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE groups FROM PUBLIC;
REVOKE ALL ON TABLE groups FROM install_host_username;
GRANT ALL ON TABLE groups TO install_host_username;


--
-- Name: history; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE history FROM PUBLIC;
REVOKE ALL ON TABLE history FROM install_host_username;
GRANT ALL ON TABLE history TO install_host_username;


--
-- Name: list_activities; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE list_activities FROM PUBLIC;
REVOKE ALL ON TABLE list_activities FROM install_host_username;
GRANT ALL ON TABLE list_activities TO install_host_username;


--
-- Name: list_ambits; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE list_ambits FROM PUBLIC;
REVOKE ALL ON TABLE list_ambits FROM install_host_username;
GRANT ALL ON TABLE list_ambits TO install_host_username;


--
-- Name: list_mps; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE list_mps FROM PUBLIC;
REVOKE ALL ON TABLE list_mps FROM install_host_username;
GRANT ALL ON TABLE list_mps TO install_host_username;


--
-- Name: list_processes; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE list_processes FROM PUBLIC;
REVOKE ALL ON TABLE list_processes FROM install_host_username;
GRANT ALL ON TABLE list_processes TO install_host_username;


--
-- Name: list_vars; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE list_vars FROM PUBLIC;
REVOKE ALL ON TABLE list_vars FROM install_host_username;
GRANT ALL ON TABLE list_vars TO install_host_username;


--
-- Name: list_vars_values; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE list_vars_values FROM PUBLIC;
REVOKE ALL ON TABLE list_vars_values FROM install_host_username;
GRANT ALL ON TABLE list_vars_values TO install_host_username;


--
-- Name: login_attempts; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE login_attempts FROM PUBLIC;
REVOKE ALL ON TABLE login_attempts FROM install_host_username;
GRANT ALL ON TABLE login_attempts TO install_host_username;


--
-- Name: memos; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE memos FROM PUBLIC;
REVOKE ALL ON TABLE memos FROM install_host_username;
GRANT ALL ON TABLE memos TO install_host_username;


--
-- Name: setup_activities_attachments; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE setup_activities_attachments FROM PUBLIC;
REVOKE ALL ON TABLE setup_activities_attachments FROM install_host_username;
GRANT ALL ON TABLE setup_activities_attachments TO install_host_username;


--
-- Name: setup_attachments; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE setup_attachments FROM PUBLIC;
REVOKE ALL ON TABLE setup_attachments FROM install_host_username;
GRANT ALL ON TABLE setup_attachments TO install_host_username;


--
-- Name: setup_company_roles; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE setup_company_roles FROM PUBLIC;
REVOKE ALL ON TABLE setup_company_roles FROM install_host_username;
GRANT ALL ON TABLE setup_company_roles TO install_host_username;


--
-- Name: setup_default_vars; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE setup_default_vars FROM PUBLIC;
REVOKE ALL ON TABLE setup_default_vars FROM install_host_username;
GRANT ALL ON TABLE setup_default_vars TO install_host_username;


--
-- Name: setup_forms_attachments; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE setup_forms_attachments FROM PUBLIC;
REVOKE ALL ON TABLE setup_forms_attachments FROM install_host_username;
GRANT ALL ON TABLE setup_forms_attachments TO install_host_username;


--
-- Name: setup_roles; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE setup_roles FROM PUBLIC;
REVOKE ALL ON TABLE setup_roles FROM install_host_username;
GRANT ALL ON TABLE setup_roles TO install_host_username;


--
-- Name: threads_acl; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE threads_acl FROM PUBLIC;
REVOKE ALL ON TABLE threads_acl FROM install_host_username;
GRANT ALL ON TABLE threads_acl TO install_host_username;


--
-- Name: users; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE users FROM PUBLIC;
REVOKE ALL ON TABLE users FROM install_host_username;
GRANT ALL ON TABLE users TO install_host_username;


--
-- Name: users_groups; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE users_groups FROM PUBLIC;
REVOKE ALL ON TABLE users_groups FROM install_host_username;
GRANT ALL ON TABLE users_groups TO install_host_username;


--
-- Name: vars; Type: ACL; Schema: public; Owner: install_host_username
--

REVOKE ALL ON TABLE vars FROM PUBLIC;
REVOKE ALL ON TABLE vars FROM install_host_username;
GRANT ALL ON TABLE vars TO install_host_username;


--
-- wmanagerQL database dump complete
--

